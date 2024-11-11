<?php

namespace Newageerp\SfEntity;

use Doctrine\ORM\EntityManager;

class SfEntityService
{

    public static function entityByName(string $name)
    {
        if (class_exists('App\Entity\\' . $name)) {
            return 'App\Entity\\' . $name;
        }
        return 'Newageerp\SfEntity\Entity\\' . $name;
    }

    public static function isEntity(EntityManager $em, string|object $class): bool
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return ! $em->getMetadataFactory()->isTransient($class);
    }
}
