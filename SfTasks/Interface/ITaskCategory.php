<?php

namespace Newageerp\SfTasks\Interface;

interface ITaskCategory
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getColor(): string;

    /**
     * @param string $color
     */
    public function setColor(string $color): void;
}