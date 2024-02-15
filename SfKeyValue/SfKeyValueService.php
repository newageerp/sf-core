<?php

namespace Newageerp\SfKeyValue;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfEntity\Entity\SfKeyValueOrm;
use Newageerp\SfEntity\Repository\SfKeyValueOrmRepository;

class SfKeyValueService
{
    protected SfKeyValueOrmRepository $sfKeyValueOrmRepository;

    protected EntityManagerInterface $em;

    public function __construct(
        SfKeyValueOrmRepository $sfKeyValueOrmRepository,
        EntityManagerInterface $em,
    ) {
        $this->sfKeyValueOrmRepository = $sfKeyValueOrmRepository;
        $this->em = $em;
    }

    public function getByKeyOrNull(string $key) : ?SfKeyValueOrm
    {
        return $this->sfKeyValueOrmRepository->findOneBy(['sfKey' => $key]);
    }

    public function getByKey(string $key) : SfKeyValueOrm
    {
        $orm = $this->getByKeyOrNull($key);
        if (!$orm) {
            $orm = new SfKeyValueOrm();
            $orm->setSfKey($key);
            $this->em->persist($orm);
        }
        return $orm;
    }

    public function insert(string $sfKey, string $sfValue) : SfKeyValueOrm
    {
        $orm = new SfKeyValueOrm();
        $orm->setSfKey($sfKey);
        $orm->setSfValue($sfValue);
        $this->em->persist($orm);
        $this->flush();
        return $orm;
    }

    public function insertIfNotExists(string $sfKey, string $sfValue) : SfKeyValueOrm
    {
        $orm = $this->getByKeyOrNull($sfKey);
        if (!$orm) {
            $orm = new SfKeyValueOrm();
            $orm->setSfKey($sfKey);
            $orm->setSfValue($sfValue);
            $this->em->persist($orm);
            $this->flush();
        }
        return $orm;
    }

    public function update(string $sfKey, string $sfValue) : ?SfKeyValueOrm
    {
        $orm = $this->getByKeyOrNull($sfKey);
        if ($orm) {
            $orm->setSfValue($sfValue);
            $this->flush();
        }
        return $orm;
    }

    public function upsert(string $sfKey, string $sfValue) : SfKeyValueOrm
    {
        $orm = $this->getByKey($sfKey);
        $orm->setSfValue($sfValue);
        $this->flush();
        return $orm;
    }

    public function flush()
    {
        $this->em->flush();
    }
}
