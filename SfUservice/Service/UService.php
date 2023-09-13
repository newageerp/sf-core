<?php

namespace Newageerp\SfUservice\Service;

use Doctrine\Persistence\ObjectRepository;
use Newageerp\SfUservice\Events\UConvertEvent;
use Newageerp\SfAuth\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Newageerp\SfSerializer\Serializer\ObjectSerializer;
use Newageerp\SfUservice\Events\UBeforeCreateAfterSetEvent;
use Newageerp\SfUservice\Events\UBeforeCreateEvent;
use Newageerp\SfUservice\Events\UBeforeUpdateAfterSetEvent;
use Newageerp\SfUservice\Events\UBeforeUpdateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfUservice\Events\UOnSaveEvent;
use Newageerp\SfUservice\Events\UPermissionsEvent;

class UService
{
    protected EntityManagerInterface $em;

    protected EventDispatcherInterface $eventDispatcher;


    protected PropertiesUtilsV3 $propertiesUtilsV3;
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected array $schemas = [];

    public function __construct(
        EntityManagerInterface     $em,
        EventDispatcherInterface $eventDispatcher,
        PropertiesUtilsV3 $propertiesUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
    ) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
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

        return 'App\Entity\\' . $entityClass;
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
                $user, $filters, $schema
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

        $params = [];
        $joins = [];

        $debug = false;

        foreach ($filters as $filter) {
            $statements = $this->getStatementsFromFilters($qb, $className, $filter, $debug, $joins, $params, $classicMode);
            if ($statements && !$debug) {
                $qb->andWhere($statements);
            }
        }

        foreach ($params as $key => $val) {
            $qb->setParameter($key, $val);
        }

        foreach ($joins as $join => $alias) {
            $qb->leftJoin($join, $alias);
        }

        foreach ($sort as $sortEl) {
            [$subJoins, $mainAlias, $alias, $fieldKey, $uuid] = $this->joinsByKey($sortEl['key']);

            $qb->addOrderBy($alias . '.' . $fieldKey, $sortEl['value']);
            foreach ($subJoins as $join => $alias) {
                $qb->leftJoin($join, $alias);
            }
        }

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

        if (!$skipPermissionsCheck) {
            $event = new UPermissionsEvent(
                $user, $filters, $schema
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

            $params = [];
            $joins = [];

            foreach ($filters as $filter) {
                $statements = $this->getStatementsFromFilters($qb, $className, $filter, false, $joins, $params, $classicMode);
                if ($statements) {
                    $qb->andWhere($statements);
                }
            }


            foreach ($params as $key => $val) {
                $qb->setParameter($key, $val);
            }

            foreach ($joins as $join => $alias) {
                $qb->leftJoin($join, $alias);
            }

            foreach ($sort as $sortEl) {
                [$subJoins,, $alias, $fieldKey,] = $this->joinsByKey($sortEl['key']);

                $qb->addOrderBy($alias . '.' . $fieldKey, $sortEl['value']);
                foreach ($subJoins as $join => $alias) {
                    $qb->leftJoin($join, $alias);
                }
            }

            $pagingQb = clone $qb;

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


    protected function getStatementsFromFilters(
        QueryBuilder $qb,
        $className,
        array        $filter,
        bool         $debug,
        &$joins,
        &$params,
        $classicMode = false
    ) {
        $statements = null;
        $loopKey = '';
        if (isset($filter['and'])) {
            $statements = $qb->expr()->andX();
            $loopKey = 'and';
        } else if (isset($filter['or'])) {
            $statements = $qb->expr()->orX();
            $loopKey = 'or';
        }

        if ($statements) {
            foreach ($filter[$loopKey] as $st) {
                if (isset($st['or']) || isset($st['and'])) {
                    $subStatements = $this->getStatementsFromFilters($qb, $className, $st, $debug, $joins, $params, $classicMode);
                    $statements->add($subStatements);
                } else {
                    $fieldDirectSelect = isset($st[3]) ? $st[3] : $classicMode;

                    $value = $st[2];
                    if ($value === 'CURRENT_USER' && AuthService::getInstance()->getUser()) {
                        $value = AuthService::getInstance()->getUser()->getId();
                    }
                    $op = '';
                    $opIsNot = false;
                    $needBrackets = false;
                    $skipParams = false;

                    $pureSql = false;
                    
                    if (isset($st[1]) && $st[1] === 'IS_EMPTY') {
                        $op = '=';
                        $value = '';
                        $skipParams = true;
                    } else if (isset($st[1]) && $st[1] === 'contains') {
                        $op = 'like';
                        $value = '%' . $st[2] . '%';
                    } else if (isset($st[1]) && ($st[1] === 'eq' || $st[1] === 'equal' || $st[1] === 'equals')) {
                        $op = 'like';
                        $value = $st[2];
                    } else if (isset($st[1]) && $st[1] === 'start') {
                        $op = 'like';
                        $value = $st[2] . '%';
                    } else if (isset($st[1]) && $st[1] === 'end') {
                        $op = 'like';
                        $value = '%' . $st[2];
                    } else if (isset($st[1]) && $st[1] === 'not_contains') {
                        if ($fieldDirectSelect) {
                            $op = 'not like';
                            $value = '%' . $st[2] . '%';
                        } else {
                            $op = 'like';
                            $opIsNot = true;
                            $value = '%' . $st[2] . '%';
                        }
                    } else if (isset($st[1]) && $st[1] === 'not_eq') {
                        if ($fieldDirectSelect) {
                            $op = 'not like';
                            $value = $st[2];
                        } else {
                            $op = 'like';
                            $opIsNot = true;
                            $value = $st[2];
                        }
                    } else if (isset($st[1]) && $st[1] === 'not_start') {
                        if ($fieldDirectSelect) {
                            $op = 'not like';
                            $value = $st[2] . '%';
                        } else {
                            $op = 'like';
                            $opIsNot = true;
                            $value = $st[2] . '%';
                        }
                    } else if (isset($st[1]) && $st[1] === 'not_end') {
                        if ($fieldDirectSelect) {
                            $op = 'not like';
                            $value = '%' . $st[2];
                        } else {
                            $op = 'like';
                            $value = '%' . $st[2];
                            $opIsNot = true;
                        }
                    } else if (isset($st[1]) && ($st[1] === 'num_eq' || $st[1] === '=')) {
                        $op = '=';
                    } else if (isset($st[1]) && ($st[1] === 'num_not_eq' || $st[1] === '!=')) {
                        $op = '!=';
                    } else if (isset($st[1]) && ($st[1] === 'gt' || $st[1] === '>')) {
                        $op = '>';
                    } else if (isset($st[1]) && ($st[1] === 'gte' || $st[1] === '>=')) {
                        $op = '>=';
                    } else if (isset($st[1]) && ($st[1] === 'lt' || $st[1] === '<')) {
                        $op = '<';
                    } else if (isset($st[1]) && ($st[1] === 'lte' || $st[1] === '<=')) {
                        $op = '<=';
                    } else if (isset($st[1]) && $st[1] === 'dgt') {
                        $op = '>';
                        $value = new \DateTime($st[2] . ' 00:00:00');
                    } else if (isset($st[1]) && $st[1] === 'dgte') {
                        $op = '>=';
                        $value = new \DateTime($st[2] . ' 00:00:00');
                    } else if (isset($st[1]) && $st[1] === 'dlt') {
                        $op = '<';
                        $value = new \DateTime($st[2] . ' 00:00:00');
                    } else if (isset($st[1]) && $st[1] === 'dlte') {
                        $op = '<=';
                        $value = new \DateTime($st[2] . ' 23:59:59');
                    } else if (isset($st[1]) && $st[1] === 'deq') {
                        $op = '=';
                        $value = new \DateTime($st[2]);
                        $skipParams = true;
                    } else if (isset($st[1]) && $st[1] === 'not_deq') {
                        if ($fieldDirectSelect) {
                            $op = '!=';
                            $value = new \DateTime($st[2]);
                        } else {
                            $op = '=';
                            $opIsNot = true;
                            $value = new \DateTime($st[2]);
                        }
                    } else if (isset($st[1]) && $st[1] === 'in') {
                        $op = 'in';
                        $needBrackets = true;
                    } else if (isset($st[1]) && $st[1] === 'not_in') {
                        if ($fieldDirectSelect) {
                            $op = 'not in';
                            $needBrackets = true;
                        } else {
                            $op = 'in';
                            $opIsNot = true;
                            $needBrackets = true;
                        }
                    } else if (isset($st[1]) && ($st[1] === 'JSON_EXTRACT' || $st[1] === 'JSON_SEARCH' || $st[1] === 'JSON_CONTAINS' || $st[1] === 'JSON_NOT_CONTAINS' || $st[1] === 'IS_NOT_NULL' || $st[1] === 'IS_NULL')) {
                        $op = 'CUSTOM';
                        if ($st[1] === 'IS_NOT_NULL' || $st[1] === 'IS_NULL') {
                            $skipParams = true;
                        }
                    }

                    if ($op) {
                        $extraKey = null;
                        if (isset($st[1]) && $st[1] === 'JSON_EXTRACT') {
                            $fA = explode(".", $st[0]);
                            array_shift($fA);
                            $st[0] = 'i.' . $fA[0];
                            array_shift($fA);
                            $extraKey = implode(".", $fA);
                        }
                        [$subJoins, $mainAlias, $alias, $fieldKey, $uuid] = $this->joinsByKey($st[0]);

                        if (!$skipParams) {
                            $params[$uuid] = $value;
                        }

                        $subQ = $this->em->createQueryBuilder()
                            ->select($mainAlias)
                            ->from($className, $mainAlias, null);

                        // PURE SQL
                        $statement = '';

                        if (isset($st[1]) && $st[1] === 'IS_NULL') {
                            $statement = $qb->expr()->isNull($alias . '.' . $fieldKey);
                        } else if (isset($st[1]) && $st[1] === 'IS_NOT_NULL') {
                            $statement = $qb->expr()->isNotNull($alias . '.' . $fieldKey);
                        } else if (isset($st[1]) && $st[1] === 'JSON_EXTRACT') {
                            $statement = "JSON_EXTRACT(" . $alias . '.' . $fieldKey . ", '$." . $extraKey . "') = :" . $uuid . "";
                        } else if (isset($st[1]) && $st[1] === 'JSON_CONTAINS') {
                            $statement = "JSON_CONTAINS(" . $alias . '.' . $fieldKey . ", :" . $uuid . ", '$') = 1";
                        } else if (isset($st[1]) && $st[1] === 'JSON_NOT_CONTAINS') {
                            $statement = "JSON_CONTAINS(" . $alias . '.' . $fieldKey . ", :" . $uuid . ", '$') != 1";
                        } else if (isset($st[1]) && $st[1] === 'JSON_SEARCH') {
                            $statement = "JSON_SEARCH(" . $alias . '.' . $fieldKey . ", 'one', :" . $uuid . ") IS NOT NULL";
                        } else if (isset($st[1]) && $st[1] === 'deq') {

                            $valueG = new \DateTime($st[2] . ' 00:00:00');
                            $valueL = new \DateTime($st[2] . ' 23:59:59');


                            $params[$uuid . 'Min'] = $valueG;
                            $params[$uuid . 'Max'] = $valueL;

                            $statement = '' . $alias . '.' . $fieldKey . ' BETWEEN :' . $uuid . 'Min AND :' . $uuid . 'Max';
                        } else {
                            $statement = $alias . '.' . $fieldKey . ' ' . $op . ' ';
                            if ($needBrackets) {
                                $statement .= '(';
                            }
                            $statement .= ':' . $uuid;
                            if ($needBrackets) {
                                $statement .= ')';
                            }
                        }


                        // foreach ($subParams as $key => $val) {
                        //     $subQ->setParameter($key, $val);
                        // }
                        // $this->ajLogger->warning('SUB JOIN open');
                        foreach ($subJoins as $join => $alias) {
                            if ($fieldDirectSelect) {
                                $qb->leftJoin($join, $alias);
                            } else {
                                $subQ->leftJoin($join, $alias);
                            }
                            // $this->ajLogger->warning('SUB JOIN ' . $join . ' ' . $alias);
                        }


                        if (!$debug) {
                            if ($fieldDirectSelect) {
                                if ($opIsNot) {
                                    $statements->add($qb->expr()->not($statement));
                                } else {
                                    $statements->add($statement);
                                }
                            } else {
                                $subQ->andWhere($statement);
                            }
                        }

                        if (!$fieldDirectSelect) {
                            if ($opIsNot) {
                                $statements->add($qb->expr()->not($qb->expr()->exists($subQ->getDQL())));
                            } else {
                                $statements->add($qb->expr()->exists($subQ->getDQL()));
                            }
                        }

                        $log['f'][] = $statement;
                    }
                }
            }
        }
        return $statements;
    }

    protected function joinsByKey($key)
    {
        $uuid = 'P' . str_replace('-', '', Uuid::uuid4()->toString());
        $mainAlias = 'A' . str_replace('-', '', Uuid::uuid4()->toString());

        $tmp = explode(".", $key);
        $alias = $tmp[0];
        $fieldKey = $tmp[count($tmp) - 1];
        $subJoins = [];
        for ($i = 1; $i < count($tmp) - 1; $i++) {
            $alias = implode("", array_slice($tmp, 0, $i + 1)) . $mainAlias;
            $join = implode("", array_slice($tmp, 0, $i)) . ($i > 1 ? $mainAlias : '') . '.' . $tmp[$i];
            $subJoins[$join] = $alias;
        }

        return [$subJoins, $mainAlias, $alias, $fieldKey, $uuid];
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
                    $className = 'App\Entity\\' . ucfirst($asArray[1]);
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
