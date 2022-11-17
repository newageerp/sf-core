<?php

namespace Newageerp\SfPaypal\Service;

use Newageerp\SfPaypal\Object\PaypalOrder;
use Newageerp\SfPaypal\Object\PaypalOrderItem;

class PaypalService
{
    protected string $endpointUrl = '';

    public function __construct(string $endpointUrl)
    {
        $this->endpointUrl = $endpointUrl;
    }

    public function captureOrder(string $token)
    {
        $url = $this->getEndpointUrl() . '/api/captureOrder';

        $ppData = [
            'token' => $token
        ];

        $ch = curl_init($url);
        $payload = json_encode($ppData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }

    public function getOrder(string $token)
    {
        $url = $this->getEndpointUrl() . '/api/getOrder';

        $ppData = [
            'token' => $token
        ];

        $ch = curl_init($url);
        $payload = json_encode($ppData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }

    public function startOrder(PaypalOrder $ppOrder)
    {
        $ppData = [
            'data' => [
                'id' => $ppOrder->getId() . ':' . time(),
                'amount' => [
                    'value' => round($ppOrder->getTotal(), 2),
                    'currency_code' => $ppOrder->getCurrency(),
                ],
                'items' => array_values(
                    array_map(
                        function (PaypalOrderItem $item) use ($ppOrder) {
                            return [
                                'name' => $item->getName(),
                                'unit_amount' => [
                                    'value' => round($item->getTotal(), 2),
                                    'currency_code' => $ppOrder->getCurrency(),
                                ],
                                'quantity' => $item->getQuantity(),
                                'sku' => $item->getSku()
                            ];
                        },
                        $ppOrder->getItems()
                    )
                )
            ],
            'callback' => [
                'success' => $ppOrder->getSuccessRedirect(),
                'error' => $ppOrder->getFailRedirect(),
            ]
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
