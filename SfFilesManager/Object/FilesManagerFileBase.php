<?php

namespace Newageerp\SfFilesManager\Object;

use Newageerp\SfBaseEntity\Object\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class FilesManagerFileBase extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     */
    protected string $fileName = '';

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property (format="string")
     */
    protected ?\DateTime $uploadTime = null;

    /**
     * @ORM\Column (type="integer")
     */
    protected int $filesSize = 0;

    /**
     * @ORM\ManyToOne(targetEntity="FilesManagerFolder", inversedBy="files")
     */
    protected ?FilesManagerFolderBase $folder = null;

    /**
     * @ORM\Column (type="json")
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    protected array $shared = [];

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
     * @return FilesManagerFolderBase|null
     */
    public function getFolder(): ?FilesManagerFolderBase
    {
        return $this->folder;
    }

    /**
     * @param FilesManagerFolderBase|null $folder
     */
    public function setFolder(?FilesManagerFolderBase $folder): void
    {
        $this->folder = $folder;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return \DateTime|null
     */
    public function getUploadTime(): ?\DateTime
    {
        return $this->uploadTime;
    }

    /**
     * @param \DateTime|null $uploadTime
     */
    public function setUploadTime(?\DateTime $uploadTime): void
    {
        $this->uploadTime = $uploadTime;
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

}