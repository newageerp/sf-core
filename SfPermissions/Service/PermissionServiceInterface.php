<?php

namespace Newageerp\SfPermissions\Service;

use Newageerp\SfBaseEntity\Object\BaseUser;

interface PermissionServiceInterface
{
    public function extendFilters(BaseUser $user, array &$filters, string $schema);
}