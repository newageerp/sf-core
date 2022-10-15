<?php
namespace Newageerp\SfFollowUp\Object;

use Newageerp\SfBaseEntity\Object\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class BaseFollowUp extends BaseEntity
{
    /**
     * @ORM\Column(type="date", nullable=true)
     * @OA\Property(type="string", format="date")
     */
    protected ?\DateTime $onDate = null;

    /**
     * @ORM\Column(type="string")
     */
    protected string $comment = '';

    /**
     * @ORM\Column(type="integer")
     */
    protected int $parentId = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected string $parentSchema = '';

    public function getOnDate(): ?\DateTime
    {
        return $this->onDate;
    }

    public function setOnDate($onDate)
    {
        $this->onDate = $onDate;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    public function getParentSchema(): string
    {
        return $this->parentSchema;
    }

    public function setParentSchema($parentSchema)
    {
        $this->parentSchema = $parentSchema;
    }

    public function getComment() : string
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}