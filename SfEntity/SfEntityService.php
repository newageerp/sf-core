<?php
namespace Newageerp\SfEntity;

class SfEntityService {

    public static function entityByName(string $name) {
        if (class_exists('App\Entity\\' . $name)) {
            return 'App\Entity\\' . $name;
        }
        return 'Newageerp\SfEntity\Entity\\' . $name;
    }

}