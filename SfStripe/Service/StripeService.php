<?php

namespace Newageerp\SfStripe\Service;

use Newageerp\SfStripe\Object\StripeOrder;

class StripeService
{
    protected string $endpointUrl = '';

    public function __construct(string $endpointUrl)
    {
        $this->endpointUrl = $endpointUrl;
    }

    public function startOrder(StripeOrder $ppOrder)
    {
        $ppData = [
            'data' => [
                'id' => $ppOrder->getId() . ':' . time(),
                'amount' => [
                    'value' => round($ppOrder->getTotal(), 2),
                    'currency_code' => $ppOrder->getCurrency(),
                ],
                'email' => $ppOrder->getEmail(),
                'paymentMethod' => $ppOrder->getPaymentMethod(),
            ],
        ];

        $url = $this->getEndpointUrl() . '/api/startOrder';

        $ch = curl_init($url);
        $payload = json_encode($ppData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }

    /**
     * Get the value of endpointUrl
     *
     * @return string
     */
    public function getEndpointUrl(): string
    {
        return $this->endpointUrl;
    }

    /**
     * Set the value of endpointUrl
     *
     * @param string $endpointUrl
     *
     * @return self
     */
    public function setEndpointUrl(string $endpointUrl): self
    {
        $this->endpointUrl = $endpointUrl;

        return $this;
    }
}
