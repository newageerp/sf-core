<?php
namespace Newageerp\SfSerialNumber\Object;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class SerialNumberBase
{
    /**
     * @ORM\Column(type="string")
     */
    protected string $className = '';

    /**
     * @ORM\Column(type="string")
     */
    protected string $incrementKey = '';

    /**
     * @ORM\Column(type="integer")
     */
    protected int $incrementValue = 0;

    public function getIncrementKey(): string
    {
        return $this->incrementKey;
    }

    public function setIncrementKey($incrementKey)
    {
        $this->incrementKey = $incrementKey;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName($className)
    {
        $this->className = $className;
    }

    public function getIncrementValue(): int
    {
        return $this->incrementValue;
    }

    public function setIncrementValue($incrementValue)
    {
        $this->incrementValue = $incrementValue;
    }

    public function increment($padLeft = 3)
    {
        $this->incrementValue++;
        return str_pad($this->incrementValue, $padLeft, "0", STR_PAD_LEFT);
    }
}