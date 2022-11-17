<?php

namespace Newageerp\SfPaypal\Object;

class PaypalOrder
{
    protected string $id = '';

    protected string $currency = '';

    protected string $successRedirect = '';

    protected string $failRedirect = '';

    /**
     * @var PaypalOrderItem[] $items
     */
    protected array $items = [];

    public function __construct(
        string $id,
        string $currency,
        string $successRedirect,
        string $failRedirect,
    ) {
        $this->id = $id;
        $this->currency = $currency;
        $this->successRedirect = $successRedirect;
        $this->failRedirect = $failRedirect;
    }

    public function addItem(PaypalOrderItem $item)
    {
        $this->items[] = $item;
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
     * Get the value of successRedirect
     *
     * @return string
     */
    public function getSuccessRedirect(): string
    {
        return $this->successRedirect;
    }

    /**
     * Set the value of successRedirect
     *
     * @param string $successRedirect
     *
     * @return self
     */
    public function setSuccessRedirect(string $successRedirect): self
    {
        $this->successRedirect = $successRedirect;

        return $this;
    }

    /**
     * Get the value of failRedirect
     *
     * @return string
     */
    public function getFailRedirect(): string
    {
        return $this->failRedirect;
    }

    /**
     * Set the value of failRedirect
     *
     * @param string $failRedirect
     *
     * @return self
     */
    public function setFailRedirect(string $failRedirect): self
    {
        $this->failRedirect = $failRedirect;

        return $this;
    }

    /**
     * Get the value of items
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @param array $items
     *
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;
        if (count($this->getItems()) > 0) {
            $total = array_sum(
                array_map(
                    function (PaypalOrderItem $item) {
                        return $item->getTotal();
                    },
                    $this->getItems()
                )
            );
        }
        return round($total, 2);
    }
}
