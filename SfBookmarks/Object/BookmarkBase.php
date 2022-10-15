<?php

namespace Newageerp\SfBookmarks\Object;

use Newageerp\SfBaseEntity\Object\BaseEntity;
use Newageerp\SfBookmarks\Interface\IBookmark;
use Doctrine\ORM\Mapping as ORM;

class BookmarkBase extends BaseEntity implements IBookmark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="integer")
     */
    protected int $parentId = 0;

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getParentSchema(): string
    {
        return $this->parentSchema;
    }

    /**
     * @param string $parentSchema
     */
    public function setParentSchema(string $parentSchema): void
    {
        $this->parentSchema = $parentSchema;
    }

    /**
     * @ORM\Column(type="string")
     */
    protected string $parentSchema = '';
}