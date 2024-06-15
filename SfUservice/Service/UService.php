<?php

namespace Newageerp\SfUservice\Service;

use Doctrine\Persistence\ObjectRepository;
use Newageerp\SfUservice\Events\UConvertEvent;
use Newageerp\SfAuth\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Newageerp\SfSerializer\Serializer\ObjectSerializer;
use Newageerp\SfUservice\Events\UBeforeCreateAfterSetEvent;
use Newageerp\SfUservice\Events\UBeforeCreateEvent;
use Newageerp\SfUservice\Events\UBeforeUpdateAfterSetEvent;
use Newageerp\SfUservice\Events\UBeforeUpdateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfEntity\SfEntityService;
use Newageerp\SfUservice\Events\UOnSaveEvent;
use Newageerp\SfUservice\Events\UPermissionsEvent;

class UService
{
    protected EntityManagerInterface $em;

    protected EventDispatcherInterface $eventDispatcher;

    protected UServiceFilter $uServiceFilter;
    protected UServiceStatements $uServiceStatements;

    protected PropertiesUtilsV3 $propertiesUtilsV3;
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected array $schemas = [];

    public function __construct(
        EntityManagerInterface     $em,
        EventDispatcherInterface $eventDispatcher,
        PropertiesUtilsV3 $propertiesUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
        UServiceFilter $uServiceFilter,
        UServiceStatements $uServiceStatements,
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->uServiceFilter = $uServiceFilter;
        $this->uServiceStatements = $uServiceStatements;
    }

    public function getEntityFromSchemaAndId(string $schema, int $id)
    {
        $entityClass = $this->convertSchemaToEntity($schema);
        if ($id === 0) {
            $entity = new $entityClass();
        } else {
            $repo = $this->em->getRepository($entityClass);
            $entity = $repo->find($id);
        }
        return $entity;
    }

    protected function convertSchemaToEntity(string $schema)
    {
        $entityClass = implode('', array_map('ucfirst', explode("-", $schema)));

        return SfEntityService::entityByName($entityClass);
    }

    public function getGroupedListDataForSchema(
        string $schema,
        array $filters,
        array $sort,
        array $summary,
        bool  $skipPermissionsCheck = false,
    ) {
        $user = AuthService::getInstance()->getUser();
        if (!$user) {
            throw new \Exception('Invalid user');
        }

        $className = $this->convertSchemaToEntity($schema);

        if (!$skipPermissionsCheck) {
            $event = new UPermissionsEvent(
                $user,
                $filters,
                $schema
            );
            $this->eventDispatcher->dispatch($event, UPermissionsEvent::NAME);
            $filters = $event->getFilters();
        }

        $classicMode = false;
        if (isset($filters[0]['classicMode'])) {
            $classicMode = $filters[0]['classicMode'];
            unset($filters[0]['classicMode']);
        }
        if (isset($filters[1]['classicMode'])) {
            $classicMode = $filters[1]['classicMode'];
            unset($filters[1]['classicMode']);
        }
        if (isset($filters[2]['classicMode'])) {
            $classicMode = $filters[2]['classicMode'];
            unset($filters[2]['classicMode']);
        }

        $alias = 'i';

        $qb = $this->em->createQueryBuilder()
            ->select($alias)
            ->from($className, $alias, null);

        $this->uServiceFilter->addQueryFilter(
            $qb,
            $filters,
            $className,
            $classicMode,
            false
        );

        $this->uServiceFilter->addQueryOrder($qb, $sort);

        $query = $qb->getQuery();

        $data = $query->getResult();

        $groupedData = [];
        $groupedTotalData = [];
        $summaryFields = [];
        $unique = [];

        foreach ($data as $result) {
            foreach ($summary as $summaryKey => $item) {
                $groupByA = explode(",", $item['groupBy']);

                $itterate = [];
                foreach ($groupByA as $key => $_g) {
                    if ($key > 0) {
                        $itterate[] = [
                            'field' => $item['field'],
                            'groupBy' => $groupByA[0],
                            'filter' => $key > 0 ? $_g : '',
                        ];
                    }
                }
                $itterate[] = [
                    'field' => $item['field'],
                    'groupBy' => $groupByA[0],
                    'filter' => '',
                ];

                foreach ($itterate as $ittKey => $itt) {
                    $itemField = $itt['field'];
                    $itemGroupBy = $itt['groupBy'];
                    $itemFilter = $itt['filter'];
                    $summaryFieldKey = $summaryKey . '_99999_' . $itemField;

                    $fieldPath = explode(".", $itemField);
                    $groupByPath = explode(".", $itemGroupBy);

                    if ($itemFilter) {
                        $filterPath = explode(".", $itemFilter);
                        $filterObj = $result;
                        foreach ($filterPath as $p) {
                            $getter = 'get' . ucfirst($p);
                            $filterObj = $filterObj->$getter();
                        }
                        $itemField = $itt['field'] . ' (' . $filterObj . ')';
                        $summaryFieldKey = $summaryKey . '_' . $ittKey . '_' . $itemField;

                        $summaryFields[$summaryFieldKey] = $item;
                        $summaryFields[$summaryFieldKey]['field'] = $itemField;
                        $summaryFields[$summaryFieldKey]['title'] = $item['title'] . ' (' . $filterObj . ')';
                        $summaryFields[$summaryFieldKey]['groupBy'] = $itemGroupBy;
                    } else {
                        $summaryFields[$summaryFieldKey] = $item;
                        $summaryFields[$summaryFieldKey]['groupBy'] = $itemGroupBy;
                    }

                    $fieldObj = $result;
                    foreach ($fieldPath as $p) {
                        $getter = 'get' . ucfirst($p);
                        $fieldObj = $fieldObj->$getter();
                    }

                    $groupByObj = $result;
                    foreach ($groupByPath as $p) {
                        $getter = 'get' . ucfirst($p);
                        if ($groupByObj && method_exists($groupByObj, $getter)) {
                            $groupByObj = $groupByObj->$getter();
                        } else {
                            $groupByObj = '';
                        }
                    }

                    if (!isset($groupedData[$itemGroupBy])) {
                        $groupedData[$itemGroupBy] = [];
                    }
                    if (!isset($groupedTotalData[$itemGroupBy])) {
                        $groupedTotalData[$itemGroupBy] = [];
                    }

                    if (!isset($groupedData[$itemGroupBy][$groupByObj])) {
                        $groupedData[$itemGroupBy][$groupByObj] = [];
                    }
                    if (!isset($groupedData[$itemGroupBy][$groupByObj][$itemField])) {
                        $groupedData[$itemGroupBy][$groupByObj][$itemField] = 0;
                    }
                    if (!isset($groupedTotalData[$itemGroupBy][$itemField])) {
                        $groupedTotalData[$itemGroupBy][$itemField] = 0;
                    }

                    if ($item['type'] === 'count') {
                        $groupedData[$itemGroupBy][$groupByObj][$itemField]++;
                        $groupedTotalData[$itemGroupBy][$itemField]++;
                    } else if ($item['type'] === 'unique') {
                        $uniqueKey = $itemGroupBy . '_' . $groupByObj . '_' . $itemField . '_' . $fieldObj;
                        if (!isset($unique[$uniqueKey])) {
                            $unique[$uniqueKey] = 1;
                            $groupedData[$itemGroupBy][$groupByObj][$itemField]++;
                            $groupedTotalData[$itemGroupBy][$itemField]++;
                        }
                    } else if ($item['type'] === 'hours') {
                        $groupedData[$itemGroupBy][$groupByObj][$itemField] += $fieldObj / 60;
                        $groupedTotalData[$itemGroupBy][$itemField] += $fieldObj / 60;
                    } else {
                        $groupedData[$itemGroupBy][$groupByObj][$itemField] += $fieldObj;
                        $groupedTotalData[$itemGroupBy][$itemField] += $fieldObj;
                    }
                }
            }
        }
        ksort($summaryFields);
        return ['data' => $groupedData, 'total' => $groupedTotalData, 'summaryFields' => array_values($summaryFields)];
    }

    public function getListDataFromCacheToken(string $token, array $override)
    {
        $cacheResult = json_decode(base64_decode($token), true);
        return $this->getListDataForSchema(
            isset($override['schema']) ? $override['schema'] : $cacheResult['schema'],
            isset($override['page']) ? $override['page'] : $cacheResult['page'],
            isset($override['pageSize']) ? $override['pageSize'] : $cacheResult['pageSize'],
            isset($override['fieldsToReturn']) ? $override['fieldsToReturn'] : $cacheResult['fieldsToReturn'],
            isset($override['filters']) ? $override['filters'] : $cacheResult['filters'],
            isset($override['extraData']) ? $override['extraData'] : $cacheResult['extraData'],
            isset($override['sort']) ? $override['sort'] : $cacheResult['sort'],
            isset($override['totals']) ? $override['totals'] : $cacheResult['totals'],
            isset($override['skipPermissionsCheck']) ? $override['skipPermissionsCheck'] : $cacheResult['skipPermissionsCheck'],
        );
    }

    protected function checkForClassicMode(array $filters)
    {
        $classicMode = false;
        if (isset($filters[0]['classicMode'])) {
            $classicMode = $filters[0]['classicMode'];
            unset($filters[0]['classicMode']);
        }
        if (isset($filters[1]['classicMode'])) {
            $classicMode = $filters[1]['classicMode'];
            unset($filters[1]['classicMode']);
        }
        if (isset($filters[2]['classicMode'])) {
            $classicMode = $filters[2]['classicMode'];
            unset($filters[2]['classicMode']);
        }
        return $classicMode;
    }

    protected function checkForPermissions(array $filters, string $schema)
    {
        $user = AuthService::getInstance()->getUser();

        $event = new UPermissionsEvent(
            $user,
            $filters,
            $schema
        );
        $this->eventDispatcher->dispatch($event, UPermissionsEvent::NAME);
        return $event->getFilters();
    }

    public function getTabChartDataForSchema(
        string $schema,
        array  $filters,
        string $chartSql,
        bool   $skipPermissionsCheck = false,
    ) {
        $user = AuthService::getInstance()->getUser();
        if (!$user) {
            throw new \Exception('Invalid user');
        }

        $className = $this->convertSchemaToEntity($schema);

        if (method_exists($className, 'getSoftRemoved')) {
            $filters[] = ['and' => [
                ['i.softRemoved', '=', false, true]
            ]];
        }

        if (!$skipPermissionsCheck) {
            $filters = $this->checkForPermissions($filters, $schema);
        }

        $classicMode = $this->checkForClassicMode($filters);

        $alias = 'i';

        $qb = $this->em->createQueryBuilder()
            ->select($alias . '.id')
            ->from($className, $alias, null);

        $this->uServiceFilter->addQueryFilter(
            $qb,
            $filters,
            $className,
            $classicMode,
            false,
        );

        $query = $qb->getQuery();

        $chartSql = str_replace('IDS_SQL', $query->getSQL(), $chartSql);

        $stmt = $this->em->getConnection()->prepare($chartSql);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        return $result;
    }

    public function getListDataForSchema(
        string $schema,
        int    $page,
        int    $pageSize,
        array  $fieldsToReturn,
        array  $filters,
        array  $extraData,
        array  $sort,
        array  $totals,
        bool   $skipPermissionsCheck = false,
    ) {
        $user = AuthService::getInstance()->getUser();
        if (!$user) {
            throw new \Exception('Invalid user');
        }

        $className = $this->convertSchemaToEntity($schema);

        if (method_exists($className, 'getSoftRemoved')) {
            $filters[] = ['and' => [
                ['i.softRemoved', '=', false, true]
            ]];
        }

        if (!$skipPermissionsCheck) {
            $filters = $this->checkForPermissions($filters, $schema);
        }

        $classicMode = $this->checkForClassicMode($filters);

        $cacheRequest = [
            'schema' => $schema,
            'page' => 1,
            'pageSize' => 999999,
            'fieldsToReturn' => $fieldsToReturn,
            'filters' => $filters,
            'extraData' => $extraData,
            'sort' => $sort,
            'totals' => $totals,
            'skipPermissionsCheck' => true,
        ];

        $totalData = [];
        $pagingData = [];
        $data = [];
        $query = null;

        if (isset($filters['empty']) && $filters['empty']) {
            $pagingData['c'] = 1;
            $entity = new $className();

            $createOptions = isset($extraData['createOptions']) ? $extraData['createOptions'] : [];

            $event = new UConvertEvent(
                $entity,
                $schema,
                isset($createOptions['convert']) ? $createOptions['convert'] : [],
                $createOptions,
                $user
            );
            $this->eventDispatcher->dispatch($event, UConvertEvent::NAME);
            $convertFieldsReturn = $event->getDataToReturn();
            $entity = $event->getEntity();

            if ($fieldsToReturn && $convertFieldsReturn) {
                $fieldsToReturn = array_merge($fieldsToReturn, $convertFieldsReturn);
            }

            $data = [$entity];
        } else {
            $alias = 'i';

            $qb = $this->em->createQueryBuilder()
                ->select($alias)
                ->from($className, $alias, null);

            $this->uServiceFilter->addQueryFilter(
                $qb,
                $filters,
                $className,
                $classicMode,
                false,
            );

            $pagingQb = clone $qb;

            $this->uServiceFilter->addQueryOrder($qb, $sort);

            if ($page > 0) {
                $firstResult = ($page - 1) * $pageSize;
                $qb->setMaxResults($pageSize)
                    ->setFirstResult($firstResult);
            }

            $query = $qb->getQuery();

            $data = $query->getResult();
            $pagingQb
                ->select('count(i.id) as c');
            foreach ($totals as $total) {
                if ($total['type'] === 'count') {
                    $pagingQb->addSelect('count(' . $total['path'] . ') as ' . $total['field'] . '');
                } else {
                    $pagingQb->addSelect('sum(' . $total['path'] . ') as ' . $total['field'] . '');
                }
            }
            $pagingData = $pagingQb->getQuery()
                ->getSingleResult();

            foreach ($totals as $total) {
                $totalData[$total['field']] = isset($pagingData[$total['field']]) ? round((float)$pagingData[$total['field']], 2) : 0;
            }
        }
        $jsonContent = array_map(function ($item) use ($fieldsToReturn) {
            return ObjectSerializer::serializeRow($item, $fieldsToReturn);
        }, $data);

        return [
            'data' => $jsonContent,
            'records' => $pagingData['c'],
            'totals' => $totalData,
            'sql' => $query ? $query->getSQL() : '',
            // 'params' => $query ? $query->getParameters() : [],
            'params' => [],
            'filters' => $filters,
            // 'log' => $log,
            'cl' => $classicMode,
            'cacheRequest' => base64_encode(json_encode($cacheRequest)),
        ];
    }

    /**
     * @throws \Exception
     */
    public function updateElement(
        $element,
        array $data,
        string $schema,
        ?array $requiredFields = [],
    ) {
        $isNew = false;
        if (!$element->getId()) {
            $ev = new UBeforeCreateEvent($element, $data, $schema);
            $this->eventDispatcher->dispatch($ev, UBeforeCreateEvent::NAME);
            $isNew = true;
        } else {
            $ev = new UBeforeUpdateEvent($element, $data, $schema);
            $this->eventDispatcher->dispatch($ev, UBeforeUpdateEvent::NAME);
        }

        foreach ($data as $key => $val) {
            if ($key === 'createdAt' || $key === 'updatedAt') {
                continue;
            }

            $prop = $this->propertiesUtilsV3->getPropertyForSchema($schema, $key);

            $type = null;
            $format = null;
            $as = null;
            if ($prop && $prop['type']) {
                $type = $prop['type'];
            }
            if ($prop && $prop['typeFormat']) {
                $format = $prop['typeFormat'];
            }
            if ($prop && $prop['as']) {
                $as = $prop['as'];
            }

            if ($type === 'string') {
                if ($format === 'date' || $format === 'datetime' || $format === 'date-time') {
                    $val = $val ? new \DateTime($val) : null;
                } else {
                    if (is_string($val)) {
                        $val = trim($val);
                    } else {
                        //                        $notString[] = $key;
                    }
                }
            }

            if ($type === 'rel') {
                if (is_array($val) && isset($val['id'])) {
                    $typeClassName = $this->convertSchemaToEntity($format);
                    $repository = $this->em->getRepository($typeClassName);
                    $val = $repository->find($val['id']);
                } else {
                    $val = null;
                }


                $mapped = null;
                if (isset($prop['additionalProperties'])) {
                    $additionalProperties = json_decode($prop['additionalProperties'], true);
                    foreach ($additionalProperties as $propAd) {
                        if (isset($propAd['mapped'])) {
                            $mapped = $propAd['mapped'];
                        }
                    }
                }
                if ($mapped) {
                    $mapGetter = 'get' . lcfirst($key);
                    $mapSetter = 'set' . lcfirst($mapped);

                    $mapEl = $element->$mapGetter();
                    if ($mapEl) {
                        $mapEl->$mapSetter(null);
                    }
                    if ($val) {
                        $val->$mapSetter($element);
                    }
                }
            }

            if ($type === 'array' && $format !== 'string') {
                $mapped = null;
                if (isset($prop['additionalProperties'])) {
                    $additionalProperties = json_decode($prop['additionalProperties'], true);
                    foreach ($additionalProperties as $propAd) {
                        if (isset($propAd['mapped'])) {
                            $mapped = $propAd['mapped'];
                        }
                    }
                }
                if ($mapped) {
                    $mainGetter = 'get' . lcfirst($key);

                    $relClassName = $this->convertSchemaToEntity($format);
                    /**
                     * @var ObjectRepository $repository
                     */
                    $relRepository = $this->em->getRepository($relClassName);

                    $relElements = $relRepository->findBy([$mapped => $element]);
                    $setter = 'set' . lcfirst($mapped);

                    $element->{$mainGetter}()->clear();

                    foreach ($relElements as $relElement) {
                        $relElement->$setter(null);
                        $this->em->persist($relElement);

                        if ($element->{$mainGetter}()->contains($relElement)) {
                            $element->{$mainGetter}()->removeElement($relElement);
                        }
                    }

                    foreach ($val as $relVal) {
                        $relElement = $relRepository->find($relVal['id']);
                        if ($relElement) {
                            $relElement->$setter($element);
                            $this->em->persist($relElement);

                            $element->$mainGetter()->add($relElement);
                        }
                    }
                } else {
                    $method = 'set' . lcfirst($key);
                    if (method_exists($element, $method)) {
                        $element->$method($val);
                    } else {
                        $skipped[] = $method;
                    }
                }
            } else {
                $method = 'set' . lcfirst($key);
                if (method_exists($element, $method)) {
                    if ($type === 'number' && $format === 'float') {
                        $element->$method((float)$val);
                    } else if ($type === 'int' || $type === 'integer' || ($type === 'number' && $format === 'integer') || ($type === 'number' && $format === 'int')) {
                        $element->$method((float)$val);
                    } else {
                        $element->$method($val);
                    }
                } else {
                    $skipped[] = $method;
                }
            }

            if ($type === 'array' && mb_strpos($as, 'entity:') === 0) {
                $method = 'set' . lcfirst($key) . 'Value';

                if (method_exists($element, $method)) {
                    $asArray = explode(":", $as);
                    $className = SfEntityService::entityByName(ucfirst($asArray[1]));
                    $repo = $this->em->getRepository($className);
                    $methodGet = 'get' . ucfirst($asArray[2]);
                    if ($val) {
                        $cache = [];
                        foreach ($val as $valId) {
                            $valObject = $repo->find($valId);
                            if ($valObject) {
                                $cache[] = $valObject->$methodGet();
                            }
                            $element->$method(implode(", ", $cache));
                        }
                    } else {
                        $element->$method('');
                    }
                }
            }
        }

        if ($isNew) {
            $ev = new UBeforeCreateAfterSetEvent($element, $data, $schema);
            $this->eventDispatcher->dispatch($ev, UBeforeCreateAfterSetEvent::NAME);
        } else {
            $ev = new UBeforeUpdateAfterSetEvent($element, $data, $schema);
            $this->eventDispatcher->dispatch($ev, UBeforeUpdateAfterSetEvent::NAME);
        }

        $event = new UOnSaveEvent($element);
        $this->eventDispatcher->dispatch($event, UOnSaveEvent::NAME);

        $requiredError = [];
        if (!(isset($data['skipRequiredCheck']) && $data['skipRequiredCheck'])) {
            $entityRequiredFields = $this->entitiesUtilsV3->getRequiredBySlug($schema);
            $requiredFields = array_merge($requiredFields, $entityRequiredFields);

            foreach ($requiredFields as $requiredField) {
                $method = 'get' . lcfirst($requiredField);
                if (method_exists($element, $method)) {
                    if (!$element->$method()) {
                        $requiredError[] = $requiredField;
                    }
                }
            }
        }
        if (count($requiredError) > 0) {
            throw new \Exception('Fill in the required fields');
        }

        $this->em->persist($element);
    }
}
