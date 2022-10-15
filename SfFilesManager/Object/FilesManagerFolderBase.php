<?php

namespace Newageerp\SfFilesManager\Object;

use Newageerp\SfBaseEntity\Object\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class FilesManagerFolderBase extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     */
    protected string $folderName = '';

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property (format="string")
     */
    protected ?\DateTime $lastUploadTime = null;

    /**
     * @ORM\Column (type="integer")
     */
    protected int $filesSize = 0;

    /**
     * @ORM\Column (type="integer")
     */
    protected int $filesCount = 0;

    /**
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    protected array $shared = [];

    /**
     * @ORM\OneToMany(targetEntity="FilesManagerFile", mappedBy="folder")
     * @OA\Property(type="array", @OA\Items(type=@Model(type=FilesManagerFile::class)), additionalProperties={{"mapped": "folder"}})
     */
    protected Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUploadTime(): ?\DateTime
    {
        return $this->lastUploadTime;
    }

    /**
     * @param \DateTime|null $lastUploadTime
     */
    public function setLastUploadTime(?\DateTime $lastUploadTime): void
    {
        $this->lastUploadTime = $lastUploadTime;
    }

    /**
     * @return int
     */
    public function getFilesSize(): int
    {
        return $this->filesSize;
    }

    /**
     * @param int $filesSize
     */
    public function setFilesSize(int $filesSize): void
    {
        $this->filesSize = $filesSize;
    }

    /**
     * @return int
     */
    public function getFilesCount(): int
    {
        return $this->filesCount;
    }

    /**
     * @param int $filesCount
     */
    public function setFilesCount(int $filesCount): void
    {
        $this->filesCount = $filesCount;
    }

    /**
     * @return array
     */
    public function getShared(): array
    {
        return $this->shared;
    }

    /**
     * @param array $shared
     */
    public function setShared(array $shared): void
    {
        $this->shared = $shared;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getFiles(): ArrayCollection|Collection
    {
        return $this->files;
    }

    /**
     * @param ArrayCollection|Collection $files
     */
    public function setFiles(ArrayCollection|Collection $files): void
    {
        $this->files = $files;
    }

    /**
     * @return string
     */
    public function getFolderName(): string
    {
        return $this->folderName;
    }

    /**
     * @param string $folderName
     */
    public function setFolderName(string $folderName): void
    {
        $this->folderName = $folderName;
    }


}