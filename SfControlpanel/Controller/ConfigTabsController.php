<?php

namespace Newageerp\SfControlpanel\Controller;

use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;

/**
 * @Route(path="/app/nae-core/config-tabs")
 */
class ConfigTabsController extends ConfigBaseController
{
    protected function getLocalStorageFile()
    {
        $file = $this->getLocalStorage() . '/tabs.json';
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
        return $file;
    }

    protected function saveBuilder($data)
    {
        file_put_contents(
            $this->getLocalStorageFile(),
            json_encode($data)
        );
    }

    /**
     * @Route(path="/tablesDataSourceSearchToolbarCustom", methods={"POST"})
     */
    public function tablesDataSourceSearchToolbarCustom(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $schema = $request->get('schema');
        $type = $request->get('type');

        $variant = $request->get('variant');

        $dataSourceCustomGeneratedPath = Utils::customFolderPath('tabs/tables-data-source');
        $dataSourceCompName = Utils::fixComponentName(ucfirst($schema) . ucfirst($type) . 'TableDataSource');

        $path = '';
        if ($variant === 'start') {
            $path = $dataSourceCustomGeneratedPath . '/' . $dataSourceCompName . 'ToolbarStartContent.tsx';
        } else if ($variant === 'end') {
            $path = $dataSourceCustomGeneratedPath . '/' . $dataSourceCompName . 'ToolbarEndContent.tsx';
        } else if ($variant === 'middle') {
            $path = $dataSourceCustomGeneratedPath . '/' . $dataSourceCompName . 'ToolbarMiddleContent.tsx';
        }

        if ($path && !file_exists($path)) {
            file_put_contents($path, '// TODO');
        }

        $output = ['data' => []];

        return $this->json($output);
    }

    /**
     * @Route(path="/listConfig", methods={"POST"})
     * @OA\Post (operationId="NaeConfigTabList")
     */
    public function listConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = ['data' => []];

        try {
            $data = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );

            if ($request->get('id')) {
                $data = array_filter(
                    $data,
                    function ($item) use ($request) {
                        return $item['id'] === $request->get('id');
                    }
                );
            }

            $output['data'] = array_values($data);
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/saveConfig", methods={"POST"})
     * @OA\Post (operationId="NaeConfigTabSave")
     */
    public function saveConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
            $item = $request->get('item');
            if (!isset($item['id']) || !$item['id']) {
                $item['id'] = Uuid::uuid4()->toString();
            }

            $isFound = false;
            $data = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );
            foreach ($data as &$el) {
                if ($el['id'] === $item['id']) {
                    $el = $item;
                    $isFound = true;
                }
            }
            if (!$isFound) {
                $data[] = $item;
            }
            unset($el);

            $this->saveBuilder($data);

            $output['data'] = $item;
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/removeConfig", methods={"POST"})
     * @OA\Post (operationId="NaeConfigTabRemove")
     */
    public function removeConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
            $id = $request->get('id');

            $tmpData = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );
            $data = [];
            foreach ($tmpData as $el) {
                if ($id !== $el['id']) {
                    $data[] = $el;
                }
            }

            $this->saveBuilder($data);

            $output['data'] = [];
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }
}
