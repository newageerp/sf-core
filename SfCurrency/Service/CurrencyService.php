<?php

namespace Newageerp\SfCurrency\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CurrencyService
{

    protected FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public function getRateFor(string $currency, ?string $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $key = $currency . '_' . $date;
        $value = $this->cache->get($key, function (ItemInterface $item) use ($date, $currency) {
            // 30 days seconds
            $item->expiresAfter(2592000);

            return $this->getRawRateFor($currency, $date);
        });
        return (float)$value;
    }

    public function getRawRateFor(string $currency, ?string $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $link = 'http://www.lb.lt/webservices/FxRates/FxRates.asmx/getFxRates';
        $fields = array(
            'tp' => 'LT',
            'dt' => $date
        );

        $curlInstance = curl_init();

        curl_setopt($curlInstance, CURLOPT_URL, $link);
        curl_setopt($curlInstance, CURLOPT_POST, count($fields));
        curl_setopt($curlInstance, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($curlInstance, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlInstance);

        curl_close($curlInstance);

        $computedValue = 1;

        $xmlData = new \SimpleXMLElement($response);
        foreach ($xmlData->FxRate as $element) {
            if ((string)$element->CcyAmt[1]->Ccy === $currency) {
                return (float)$element->CcyAmt[1]->Amt;
            }
        }

        return $computedValue;
    }
}
