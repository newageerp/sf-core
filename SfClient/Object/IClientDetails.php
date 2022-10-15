<?php

namespace Newageerp\SfClient\Object;

interface IClientDetails
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
    public function getCode(): string;

    /**
     * @param string $code
     */
    public function setCode(string $code): void;

    /**
     * @return string
     */
    public function getVatNumber(): string;

    /**
     * @param string $vatNumber
     */
    public function setVatNumber(string $vatNumber): void;
}
