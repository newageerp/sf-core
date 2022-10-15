<?php

namespace Newageerp\SfSerialNumber\Service;

use Doctrine\ORM\EntityManagerInterface;

class SerialNumberService
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findSerialNumber(string $className, string $incrementKey)
    {
        $objectClassName = 'App\Entity\SerialNumber';

        $repository = $this->em->getRepository($objectClassName);

        $serialNumber = $repository->findOneBy(
            [
                'className' => $className,
                'incrementKey' => $incrementKey
            ]
        );
        if (!$serialNumber) {
            $serialNumber = new $objectClassName();
            $serialNumber->setClassName($className);
            $serialNumber->setIncrementKey($incrementKey);
        }
        return $serialNumber;
    }
}