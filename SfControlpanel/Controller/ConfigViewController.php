<?php

namespace Newageerp\SfControlpanel\Controller;

use Newageerp\SfControlpanel\Console\ViewFormsUtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;

/**
 * @Route(path="/app/nae-core/config-view")
 */
class ConfigViewController extends ConfigBaseController
{
    protected function getLocalStorageFile()
    {
        $file = $this->getLocalStorage() . '/view.json';
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
        return $file;
    }

    protected function saveBuilder($data) {
        file_put_contents(
            $this->getLocalStorageFile(),
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * @Route(path="/listConfig", methods={"POST"})
     * @OA\Post (operationId="NaeConfigViewList")
     */
    public function listConfig(Request $request, ViewFormsUtilsV3 $viewFormsUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        $output = ['data' => []];

        try {
            $data = $viewFormsUtilsV3->getViewForms();

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
     * @OA\Post (operationId="NaeConfigViewSave")
     */
    public function saveConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
//            if (!($user = $this->findUser($request))) {
//                throw new \Exception('Invalid user');
//            }

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
     * @OA\Post (operationId="NaeConfigViewRemove")
     */
    public function removeConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
//            if (!($user = $this->findUser($request))) {
//                throw new \Exception('Invalid user');
//            }

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
