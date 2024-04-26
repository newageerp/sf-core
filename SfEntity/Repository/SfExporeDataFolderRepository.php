<?php

namespace Newageerp\SfEntity\Repository;

use Newageerp\SfEntity\Entity\SfExploreDataFolder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SfExploreDataFolder>
 *
 * @method SfExploreDataFolder|null find($id, $lockMode = null, $lockVersion = null)
 * @method SfExploreDataFolder|null findOneBy(array $criteria, array $orderBy = null)
 * @method SfExploreDataFolder[]    findAll()
 * @method SfExploreDataFolder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SfExploreDataFolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SfExploreDataFolder::class);
    }

    public function add(SfExploreDataFolder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SfExploreDataFolder $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SfExploreDataFolder[] Returns an array of SfExploreDataFolder objects
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

//    public function findOneBySomeField($value): ?SfExploreDataFolder
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
