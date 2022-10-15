<?php

namespace Newageerp\SfTasks\Object;

use Doctrine\ORM\Mapping as ORM;
use Newageerp\SfTasks\Interface\ITaskCategory;
use OpenApi\Annotations as OA;

class TaskCategoryBase implements ITaskCategory
{
    /**
     * @ORM\Column(type="string")
     */
    protected string $name = '';

    /**
     * @OA\Property(additionalProperties={{"as":"color"}})
     * @ORM\Column(type="string")
     */
    protected string $color = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}