<?php

namespace Newageerp\SfStripe\Object;

class StripeOrder
{
    protected string $id = '';

    protected float $total = 0.0;

    protected string $currency = '';

    protected string $email = '';

    protected array $paymentMethod = [];

    /**
     * @var PaypalOrderItem[] $items
     */
    protected array $items = [];

    public function __construct(
        string $id,
        float $total,
        string $currency,
        string $email,
        array $paymentMethod,
    ) {
        $this->id = $id;
        $this->total = $total;
        $this->currency = $currency;
        $this->email = $email;
        $this->paymentMethod = $paymentMethod;
    }


    /**
     * Get the value of id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param string $id
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of total
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * Set the value of total
     *
     * @param float $total
     *
     * @return self
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @param string $currency
     *
     * @return self
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of paymentMethod
     *
     * @return array
     */
    public function getPaymentMethod(): array
    {
        return $this->paymentMethod;
    }

    /**
     * Set the value of paymentMethod
     *
     * @param array $paymentMethod
     *
     * @return self
     */
    public function setPaymentMethod(array $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}
