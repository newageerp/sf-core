<?php
namespace Newageerp\SfClient\Object;

use Doctrine\ORM\Mapping as ORM;

abstract class IClientDetailsAbstract implements IClientDetails
{
    /**
     * @ORM\Column(type="string")
     */
    protected string $name = '';

    /**
     * @ORM\Column(type="string")
     */
    protected string $vatNumber = '';
    /**
     * @ORM\Column(type="string")
     */
    protected string $code = '';

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
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber
     */
    public function setVatNumber(string $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
