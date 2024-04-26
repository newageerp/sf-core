<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfExploreDataItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfExploreDataItem>
 *
 * @method SfExploreDataItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfExploreDataItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfExploreDataItem[]    findAll()
 * @method SfExploreDataItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfExploreDataItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfExploreDataItem::class);
    }

    public function add(SfExploreDataItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfExploreDataItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfExploreDataItem[] Returns an array of SfExploreDataItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SfExploreDataItem
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
