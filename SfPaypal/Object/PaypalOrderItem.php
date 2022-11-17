<?php

namespace Newageerp\SfPaypal\Object;

class PaypalOrderItem
{
    protected string $name = '';

    protected string $sku = '';

    protected float $quantity = 0.0;

    protected float $price = 0.0;

    public function __construct(
        string $name,
        string $sku,
        float $quantity,
        float $price,
    ) {
        $this->name = $name;
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->price = $price;
    }


    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of sku
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Set the value of sku
     *
     * @param string $sku
     *
     * @return self
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get the value of quantity
     *
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * Set the value of quantity
     *
     * @param float $quantity
     *
     * @return self
     */
    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get the value of price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @param float $price
     *
     * @return self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTotal() : float {
        return round($this->getQuantity() * $this->getPrice(), 2);
    }
}
