<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\Utils;
use Newageerp\SfControlpanel\Service\DocsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InPathMapConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InPathMap';

    protected DocsService $docsService;

    public function __construct(DocsService $docsService)
    {
        parent::__construct();
        $this->docsService = $docsService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $docJsonData = $this->docsService->getDocJson();

        $configPath = Utils::customFolderPath('config') . '/NaePaths.tsx';

        $paths = $docJsonData['paths'];

        $map = [];
        foreach ($paths as $path => $data) {
            foreach ($data as $method => $methodData) {
                if (!isset($methodData['operationId'])) {
                    continue;
                }
                $map[] = [
                    'id' => $methodData['operationId'],
                    'method' => $method,
                    'path' => $path,
                    'parameters' => $methodData['parameters'] ?? []
                ];
            }
        }

        $fileContent = '
// @ts-nocheck
import axios from "axios";
import { axiosInstance } from "../../v3/api/config";
';

        $fileContent .= '
export const NaePaths = ' . json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileContent .= '

export const NaeApiFunctions = {';

        foreach ($paths as $path => $data) {
            foreach ($data as $method => $methodData) {
                if (!isset($methodData['operationId'])) {
                    continue;
                }
                if (!($method === 'post' || $method === 'get')) {
                    continue;
                }

                $parameters = [];
                $replaces = [];

                if ($method === 'post') {
                    $parameters[] = 'data: any';
                }
                if (isset($methodData['parameters'])) {
                    foreach ($methodData['parameters'] as $param) {
                        $parameters[] = $param['name'] . ': ' . $param['schema']['type'];
                        $replaces[] = '.replace(\'{' . $param['name'] . '}\', ' . $param['name'] . ')';
                    }
                }
                $fileContent .= '
    \'' . $methodData['operationId'] . '\': (' . implode(',', $parameters) . ') => {
        const url = \'' . $path . '\'' . implode('', $replaces) . ';
        
        ';
                if ($method === 'post') {
                    $fileContent .= '
                    return axiosInstance.post(url, data);
                    ';
                } else {
                    $fileContent .= '
                    return axiosInstance.get(url);
                    ';
                }

                $fileContent .= '
    },
';
            }
        }

        $fileContent .= '
        }';

        file_put_contents(
            $configPath,
            $fileContent
        );


        return Command::SUCCESS;
    }
}
