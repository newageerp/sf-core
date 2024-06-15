<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfSystemSavedFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfSystemSavedFilter>
 *
 * @method SfSystemSavedFilter|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfSystemSavedFilter|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfSystemSavedFilter[]    findAll()
 * @method SfSystemSavedFilter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfSystemSavedFilterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfSystemSavedFilter::class);
    }

    public function add(SfSystemSavedFilter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfSystemSavedFilter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfSystemSavedFilter[] Returns an array of SfSystemSavedFilter objects
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

//    public function findOneBySomeField($value): ?SfSystemSavedFilter
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
