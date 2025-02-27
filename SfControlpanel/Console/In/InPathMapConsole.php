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
        return Command::SUCCESS;
//         $docJsonData = $this->docsService->getDocJson();

//         $configPath = Utils::customFolderPath('config') . '/NaePaths.tsx';

//         $paths = $docJsonData['paths'];

//         $urlMap = [
//             'get' => [],
//             'post' => [],
//         ];
//         $map = [];
//         foreach ($paths as $path => $data) {
//             foreach ($data as $method => $methodData) {
//                 if (!isset($methodData['operationId'])) {
//                     continue;
//                 }
//                 $map[] = [
//                     'id' => $methodData['operationId'],
//                     'method' => $method,
//                     'path' => $path,
//                     'parameters' => $methodData['parameters'] ?? []
//                 ];
//                 $urlMap[$method][$methodData['operationId']] = $path;
//             }
//         }

//         $fileContent = '
// import { axiosInstance } from "@newageerp/v3.bundles.utils-bundle";
// ';

//         $fileContent .= '
// export const NaePaths = ' . json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// $fileContent .= '
// export const NaePathsMap = ' . json_encode($urlMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

//         $fileContent .= '

// export const NaeApiFunctions = {';

//         foreach ($paths as $path => $data) {
//             foreach ($data as $method => $methodData) {
//                 if (!isset($methodData['operationId'])) {
//                     continue;
//                 }
//                 if (!($method === 'post' || $method === 'get')) {
//                     continue;
//                 }

//                 $parameters = [];
//                 $replaces = [];

//                 if ($method === 'post') {
//                     $parameters[] = 'data: any';
//                 }
//                 if (isset($methodData['parameters'])) {
//                     foreach ($methodData['parameters'] as $param) {
//                         $parameters[] = $param['name'] . ': ' . $param['schema']['type'];
//                         $replaces[] = '.replace(\'{' . $param['name'] . '}\', ' . $param['name'] . ')';
//                     }
//                 }
//                 $fileContent .= '
//     \'' . $methodData['operationId'] . '\': (' . implode(',', $parameters) . ') => {
//         const url = \'' . $path . '\'' . implode('', $replaces) . ';
        
//         ';
//                 if ($method === 'post') {
//                     $fileContent .= '
//                     return axiosInstance.post(url, data);
//                     ';
//                 } else {
//                     $fileContent .= '
//                     return axiosInstance.get(url);
//                     ';
//                 }

//                 $fileContent .= '
//     },
// ';
//             }
//         }

//         $fileContent .= '
//         }';

//         file_put_contents(
//             $configPath,
//             $fileContent
//         );


//         return Command::SUCCESS;
    }
}
