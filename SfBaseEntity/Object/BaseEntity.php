<?php

namespace Newageerp\SfBaseEntity\Object;

use Newageerp\SfBaseEntity\Interface\IBaseEntity;
use Newageerp\SfBaseEntity\Interface\IUser;
use OpenApi\Annotations as OA;
use Doctrine\ORM\Mapping as ORM;

class BaseEntity implements IBaseEntity
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @OA\Property(type="string", format="datetime")
     */
    protected ?\DateTime $createdAt = null;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @OA\Property(type="string", format="datetime")
     */
    protected ?\DateTime $updatedAt = null;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected ?BaseUser $doer = null;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected ?BaseUser $creator = null;
    protected bool $skipValidation = false;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function isSkipValidation(): bool
    {
        return $this->skipValidation;
    }

    /**
     * @param bool $skipValidation
     */
    public function setSkipValidation(bool $skipValidation): void
    {
        $this->skipValidation = $skipValidation;
    }

    /**
     * Get the value of doer
     *
     * @return ?BaseUser
     */
    public function getDoer(): ?BaseUser
    {
        return $this->doer;
    }

    /**
     * Set the value of doer
     *
     * @param ?BaseUser $doer
     *
     * @return self
     */
    public function setDoer(?BaseUser $doer): self
    {
        $this->doer = $doer;

        return $this;
    }

    /**
     * Get the value of creator
     *
     * @return ?BaseUser
     */
    public function getCreator(): ?BaseUser
    {
        return $this->creator;
    }

    /**
     * Set the value of creator
     *
     * @param ?BaseUser $creator
     *
     * @return self
     */
    public function setCreator(?BaseUser $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
}