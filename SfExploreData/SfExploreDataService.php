<?php

namespace Newageerp\SfExploreData;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfEntity\Repository\SfExploreDataItemRepository;

class SfExploreDataService
{
    protected SfExploreDataItemRepository $itemRepo;
    protected EntityManagerInterface $em;

    public function __construct(
        SfExploreDataItemRepository $itemRepo,
        EntityManagerInterface $em,
    ) {
        $this->itemRepo = $itemRepo;
        $this->em = $em;
    }

    public function getDataForReport(string $reportId, ?string $limit = null, ?string $extraSql = null)
    {
        $item = $this->itemRepo->findOneBy(['exploreId' => $reportId]);
        if (!$item) {
            return [];
        }
        $sql = $item->getSqlData();
        if ($extraSql) {
            $sql .= $extraSql;
        }
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }
        $stmt = $this->em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();
        return $result;
    }
}
