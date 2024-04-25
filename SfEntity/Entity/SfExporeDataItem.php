<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfExporeDataItemRepository;

/**
 * @ORM\Entity(repositoryClass=SfExporeDataItemRepository::class)
 */
class SfExporeDataItem
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
    protected string $explodeId = '';

    /**
     * @ORM\Column(type="text")
     * @OA\Property(format="text")
     */
    protected string $sqlData = '';

    /**
     * @ORM\Column(type="text")
     * @OA\Property(format="text")
     */
    protected string $sqlCount = '';

    /**
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    protected array $columns = [];

    /**
     * @ORM\ManyToOne (targetEntity="SfExporeDataFolder")
     */
    protected ?SfExporeDataFolder $folder = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the value of folder
     *
     * @return ?SfExporeDataFolder
     */
    public function getFolder(): ?SfExporeDataFolder
    {
        return $this->folder;
    }

    /**
     * Set the value of folder
     *
     * @param ?SfExporeDataFolder $folder
     *
     * @return self
     */
    public function setFolder(?SfExporeDataFolder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get the value of sqlCount
     *
     * @return string
     */
    public function getSqlCount(): string
    {
        return $this->sqlCount;
    }

    /**
     * Set the value of sqlCount
     *
     * @param string $sqlCount
     *
     * @return self
     */
    public function setSqlCount(string $sqlCount): self
    {
        $this->sqlCount = $sqlCount;

        return $this;
    }

    /**
     * Get the value of sqlData
     *
     * @return string
     */
    public function getSqlData(): string
    {
        return $this->sqlData;
    }

    /**
     * Set the value of sqlData
     *
     * @param string $sqlData
     *
     * @return self
     */
    public function setSqlData(string $sqlData): self
    {
        $this->sqlData = $sqlData;

        return $this;
    }

    /**
     * Get the value of explodeId
     *
     * @return string
     */
    public function getExplodeId(): string
    {
        return $this->explodeId;
    }

    /**
     * Set the value of explodeId
     *
     * @param string $explodeId
     *
     * @return self
     */
    public function setExplodeId(string $explodeId): self
    {
        $this->explodeId = $explodeId;

        return $this;
    }

    /**
     * Get the value of columns
     *
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Set the value of columns
     *
     * @param array $columns
     *
     * @return self
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }
}
