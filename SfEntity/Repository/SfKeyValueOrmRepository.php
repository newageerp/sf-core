<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfKeyValueOrm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfKeyValueOrm>
 *
 * @method SfKeyValueOrm|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfKeyValueOrm|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfKeyValueOrm[]    findAll()
 * @method SfKeyValueOrm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfKeyValueOrmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfKeyValueOrm::class);
    }

    public function add(SfKeyValueOrm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfKeyValueOrm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfKeyValueOrm[] Returns an array of SfKeyValueOrm objects
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

//    public function findOneBySomeField($value): ?SfKeyValueOrm
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
