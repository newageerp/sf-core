<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfExploreDataItemRepository;

/**
 * @ORM\Entity(repositoryClass=SfExploreDataItemRepository::class)
 */
class SfExploreDataItem
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
     * @ORM\Column(type="integer")
     */
    protected int $sort = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected string $exploreId = '';

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
     * @ORM\ManyToOne (targetEntity="SfExploreDataFolder")
     */
    protected ?SfExploreDataFolder $folder = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get the value of folder
     *
     * @return ?SfExploreDataFolder
     */
    public function getFolder(): ?SfExploreDataFolder
    {
        return $this->folder;
    }

    /**
     * Set the value of folder
     *
     * @param ?SfExploreDataFolder $folder
     *
     * @return self
     */
    public function setFolder(?SfExploreDataFolder $folder): self
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

    /**
     * Get the value of exploreId
     *
     * @return string
     */
    public function getExploreId(): string
    {
        return $this->exploreId;
    }

    /**
     * Set the value of exploreId
     *
     * @param string $exploreId
     *
     * @return self
     */
    public function setExploreId(string $exploreId): self
    {
        $this->exploreId = $exploreId;

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

    /**
     * Get the value of sort
     *
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * Set the value of sort
     *
     * @param int $sort
     *
     * @return self
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'sort' => $this->getSort(),
            'exploreId' => $this->getExploreId(),
            'sqlData' => $this->getSqlData(),
            'sqlCount' => $this->getSqlCount(),
            'columns' => $this->getColumns(),
            'folder' => $this->getFolder() ? $this->getFolder()->getTitle() : ''
        ];
    }
}
