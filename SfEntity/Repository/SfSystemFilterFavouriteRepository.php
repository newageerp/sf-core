<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfSystemFilterFavourite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfSystemFilterFavourite>
 *
 * @method SfSystemFilterFavourite|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfSystemFilterFavourite|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfSystemFilterFavourite[]    findAll()
 * @method SfSystemFilterFavourite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfSystemFilterFavouriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfSystemFilterFavourite::class);
    }

    public function add(SfSystemFilterFavourite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfSystemFilterFavourite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfSystemFilterFavourite[] Returns an array of SfSystemFilterFavourite objects
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

//    public function findOneBySomeField($value): ?SfSystemFilterFavourite
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
