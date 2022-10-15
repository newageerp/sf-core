<?php
namespace Newageerp\SfCrud\Interface;

use Newageerp\SfBaseEntity\Interface\IUser;

interface IConvertService
{
    public function convert(&$entity, string $schema, array $convertOptions, ?array $createOptions, ?IUser $user);
}