<?php

namespace Newageerp\SfUservice\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Newageerp\SfAuth\Service\AuthService;
use Ramsey\Uuid\Uuid;

class UServiceStatements
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function joinsByKey($key)
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

    public function getStatementsFromFilters(
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
                        // $skipParams = true;
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
}
