<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Newageerp\SfBaseEntity\Object\BaseEntity;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfSystemFilterFavouriteRepository;

/**
 * @ORM\Entity(repositoryClass=SfSystemFilterFavouriteRepository::class)
 */
class SfSystemFilterFavourite extends BaseEntity
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
    protected string $schema = '';

    /**
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    protected array $filter = [];

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get the value of filter
     *
     * @return array
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * Set the value of filter
     *
     * @param array $filter
     *
     * @return self
     */
    public function setFilter(array $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
