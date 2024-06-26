<?php

namespace Newageerp\SfControlpanel\Controller;

use Newageerp\SfControlpanel\Console\PropertiesUtils;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfSocket\Service\SocketService;
use Newageerp\SfControlpanel\Console\EditFormsUtilsV3;

/**
 * @Route(path="/app/nae-core/config-edit")
 */
class ConfigEditController extends ConfigBaseController
{
    protected PropertiesUtilsV3 $propertiesUtilsV3;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, PropertiesUtilsV3 $propertiesUtilsV3, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
    }

    protected function getLocalStorageFile()
    {
        $file = $this->getLocalStorage() . '/edit.json';
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
        return $file;
    }

    protected function saveBuilder($data)
    {
        file_put_contents(
            $this->getLocalStorageFile(),
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * @Route(path="/listConfig", methods={"POST"})
     * @OA\Post (operationId="NaeConfigEditList")
     */
    public function listConfig(Request $request, EditFormsUtilsV3 $editFormsUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        $output = ['data' => []];

        try {
            $data = $editFormsUtilsV3->getEditForms();

            if ($request->get('schema')) {
                $data = array_values(
                    array_filter($data, function ($item) use ($request) {
                        if ($item['config']['schema'] === $request->get('schema')) {
                            return true;
                        }
                        return false;
                    })
                );
            }

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
     * @OA\Post (operationId="NaeConfigEditSave")
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
                if (isset($el['config']['fields'])) {
                    foreach ($el['config']['fields'] as &$field) {
                        $naeType = '';
                        $prop = $this->propertiesUtilsV3->getPropertyForPath($field['path'], $field);
                        if ($prop) {
                            $naeType = $this->propertiesUtilsV3->getPropertyNaeType($prop, $field);
                        }
                        $field['_naeType'] = $naeType;
                    }
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
     * @OA\Post (operationId="NaeConfigEditRemove")
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
