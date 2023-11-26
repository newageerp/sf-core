<?php

namespace Newageerp\SfFiles\Event;

use Symfony\Contracts\EventDispatcher\Event;

class FileOnUploadEvent extends Event
{
    public const NAME = 'sffiles.uploadevent';

    protected string $folder;

    protected string $fileName;

    protected int $id;

    public function __construct(string $folder, string $fileName, int $id)
    {
        $this->folder = $folder;
        $this->fileName = $fileName;
        $this->id = $id;
    }

    /**
     * Get the value of folder
     *
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * Set the value of folder
     *
     * @param string $folder
     *
     * @return self
     */
    public function setFolder(string $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get the value of fileName
     *
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Set the value of fileName
     *
     * @param string $fileName
     *
     * @return self
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
