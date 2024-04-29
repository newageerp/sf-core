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

    public function getDataForReport(string $reportId)
    {
        $item = $this->itemRepo->findOneBy(['exploreId' => $reportId]);
        if (!$item) {
            return [];
        }
        $sql = $item->getSqlData();
        $stmt = $this->em->getConnection()->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();
        return $result;
    }
}
