<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfSystemSavedFilterRepository;

/**
 * @ORM\Entity(repositoryClass=SfSystemSavedFilterRepository::class)
 */
class SfSystemSavedFilter
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
    protected string $title = '';

    /**
     * @ORM\Column(type="string")
     */
    protected string $filterSchema = '';

    /**
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    protected array $filter = [];

    /**
     * @ORM\Column(type="integer")
     */
    protected int $userId = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * Get the value of userId
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @param int $userId
     *
     * @return self
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get the value of filterSchema
     *
     * @return string
     */
    public function getFilterSchema(): string
    {
        return $this->filterSchema;
    }

    /**
     * Set the value of filterSchema
     *
     * @param string $filterSchema
     *
     * @return self
     */
    public function setFilterSchema(string $filterSchema): self
    {
        $this->filterSchema = $filterSchema;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
