<?php

namespace Newageerp\SfEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Newageerp\SfEntity\Repository\SfExporeDataFolderRepository;

/**
 * @ORM\Entity(repositoryClass=SfExporeDataFolderRepository::class)
 */
class SfExporeDataFolder
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
}
