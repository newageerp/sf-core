<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfExporeDataFolder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfExporeDataFolder>
 *
 * @method SfExporeDataFolder|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfExporeDataFolder|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfExporeDataFolder[]    findAll()
 * @method SfExporeDataFolder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfExporeDataFolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfExporeDataFolder::class);
    }

    public function add(SfExporeDataFolder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfExporeDataFolder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfExporeDataFolder[] Returns an array of SfExporeDataFolder objects
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

//    public function findOneBySomeField($value): ?SfExporeDataFolder
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
