<?php

namespace Newageerp\SfUservice\Service;

use Doctrine\ORM\QueryBuilder;

class UServiceFilter
{
    protected UServiceStatements $uServiceStatements;

    public function __construct(
        UServiceStatements $uServiceStatements
    ) {
        $this->uServiceStatements = $uServiceStatements;
    }

    public function addQueryFilter(
        QueryBuilder $qb,
        array $filters,
        string $className,
        bool $classicMode,
        ?bool $isDebug = false,
    ) {
        $params = [];
        $joins = [];

        foreach ($filters as $filter) {
            $statements = $this->uServiceStatements->getStatementsFromFilters(
                $qb,
                $className,
                $filter,
                $isDebug,
                $joins,
                $params,
                $classicMode
            );
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
    }

    public function addQueryOrder(
        QueryBuilder $qb,
        array  $sort,
    ) {
        foreach ($sort as $sortEl) {
            [$subJoins,, $alias, $fieldKey,] = $this->uServiceStatements->joinsByKey($sortEl['key']);

            $qb->addOrderBy($alias . '.' . $fieldKey, $sortEl['value']);
            foreach ($subJoins as $join => $alias) {
                $qb->leftJoin($join, $alias);
            }
        }
    }
}
