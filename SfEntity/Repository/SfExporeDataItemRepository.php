<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfExporeDataItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfExporeDataItem>
 *
 * @method SfExporeDataItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfExporeDataItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfExporeDataItem[]    findAll()
 * @method SfExporeDataItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfExporeDataItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfExporeDataItem::class);
    }

    public function add(SfExporeDataItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfExporeDataItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfExporeDataItem[] Returns an array of SfExporeDataItem objects
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

//    public function findOneBySomeField($value): ?SfExporeDataItem
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
