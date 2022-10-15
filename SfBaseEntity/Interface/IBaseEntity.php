<?php
namespace Newageerp\SfBaseEntity\Interface;

use Newageerp\SfBaseEntity\Object\BaseUser;

interface IBaseEntity
{
    public function getCreatedAt(): ?\DateTime;

    public function getUpdatedAt(): ?\DateTime;

    public function getDoer(): ?BaseUser;

    public function getCreator(): ?BaseUser;

    public function isSkipValidation(): bool;
}