<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfExploreDataFolderRepository;

/**
 * @ORM\Entity(repositoryClass=SfExploreDataFolderRepository::class)
 */
class SfExploreDataFolder
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
     * @ORM\Column(type="number")
     */
    protected int $sort = 0;

    public function getId(): ?int
    {
        return $this->id;
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
}
