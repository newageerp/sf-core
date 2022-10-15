<?php
namespace Newageerp\SfBookmarks\Interface;

interface IBookmark
{
    public function getId(): ?int;

    public function getParentId(): int;

    public function getParentSchema(): string;
}