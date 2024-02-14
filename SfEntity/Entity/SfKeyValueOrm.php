<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfKeyValueOrmRepository;

/**
 * @ORM\Entity(repositoryClass=SfKeyValueOrmRepository::class)
 */
class SfKeyValueOrm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    protected string $sfKey = '';

    /**
     * @ORM\Column(type="string")
     */
    protected string $sfValue = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of sfKey
     *
     * @return string
     */
    public function getSfKey(): string
    {
        return $this->sfKey;
    }

    /**
     * Set the value of sfKey
     *
     * @param string $sfKey
     *
     * @return self
     */
    public function setSfKey(string $sfKey): self
    {
        $this->sfKey = $sfKey;

        return $this;
    }

    /**
     * Get the value of sfValue
     *
     * @return string
     */
    public function getSfValue(): string
    {
        return $this->sfValue;
    }

    /**
     * Set the value of sfValue
     *
     * @param string $sfValue
     *
     * @return self
     */
    public function setSfValue(string $sfValue): self
    {
        $this->sfValue = $sfValue;

        return $this;
    }
}
