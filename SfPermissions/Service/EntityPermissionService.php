<?php

namespace Newageerp\SfPermissions\Service;

use Newageerp\SfAuth\Service\AuthService;

class EntityPermissionService
{
    public static function checkIsEditable(?object $object): bool
    {
        if (!$object) {
            return true;
        }
        if (!method_exists($object, 'getScopes')) {
            return true;
        }
        $scopes = $object->getScopes();

        $user = AuthService::getInstance()->getUser();

        if (in_array('disable-edit', $scopes)) {
            return false;
        }
        if (in_array('disable-edit-' . $user->getPermissionGroup(), $scopes)) {
            return false;
        }
        $isAllowScope = false;
        foreach ($scopes as $scope) {
            if (mb_strpos($scope, 'allow-edit') !== false) {
                $isAllowScope = true;
            }
        }
        if ($isAllowScope) {
            if (in_array('allow-edit-' . $user->getPermissionGroup(), $scopes)) {
                return true;
            }
            return false;
        }
        return true;
    }

    public static function checkIsRemovable(?object $object): bool
    {
        if (!$object) {
            return true;
        }
        if (!method_exists($object, 'getScopes')) {
            return true;
        }
        $scopes = $object->getScopes();

        $user = AuthService::getInstance()->getUser();

        if (in_array('disable-remove', $scopes)) {
            return false;
        }
        if (in_array('disable-remove-' . $user->getPermissionGroup(), $scopes)) {
            return false;
        }
        $isAllowScope = false;
        foreach ($scopes as $scope) {
            if (mb_strpos($scope, 'allow-remove') !== false) {
                $isAllowScope = true;
            }
        }
        if ($isAllowScope) {
            if (in_array('allow-remove-' . $user->getPermissionGroup(), $scopes)) {
                return true;
            }
            return false;
        }
        return true;
    }
}
