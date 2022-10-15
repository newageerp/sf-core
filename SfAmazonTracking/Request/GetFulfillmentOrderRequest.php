<?php

namespace Newageerp\SfAmazonTracking\Request;

class GetFulfillmentOrderRequest
{
    protected string $serviceUrl = 'https://mws.amazonservices.com/FulfillmentOutboundShipment/2010-10-01';

    protected string $action = 'GetFulfillmentOrder';
    protected string $signatureMethod = 'HmacSHA256';
    protected string $signatureVersion = '2';
    protected string $apiVersion = '2010-10-01';

    protected string $sellerId;
    protected string $marketplaceId;
    protected string $sellerFulfillmentOrderId;
    protected string $awsAccessKeyId;
    protected string $MWSAuthToken;

    public function __construct(
        string $sellerId,
        string $marketplaceId,
        string $sellerFulfillmentOrderId,
        string $awsAccessKeyId,
        string $MWSAuthToken,
    )
    {
        $this->sellerId = $sellerId;
        $this->marketplaceId = $marketplaceId;
        $this->sellerFulfillmentOrderId = $sellerFulfillmentOrderId;
        $this->awsAccessKeyId = $awsAccessKeyId;
        $this->MWSAuthToken = $MWSAuthToken;
    }

    public function makeRequest()
    {
        $url = 'https://mws.amazonservices.com/FulfillmentOutboundShipment/2010-10-01?' . http_build_query($this->buildParams());

        $ch = curl_init($url);
        $payload = json_encode([]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($result);
        return $xml->GetFulfillmentOrderResult->FulfillmentShipment->member->FulfillmentShipmentPackage->member->TrackingNumber;
    }

    public function buildParams()
    {
        $params = [];

        $params['Action'] = $this->action;
        $params['Version'] = $this->apiVersion;
        $params['AWSAccessKeyId'] = $this->awsAccessKeyId;
        $params['MWSAuthToken'] = $this->MWSAuthToken;
        $params['SignatureVersion'] = $this->signatureVersion;
        $params['SignatureMethod'] = $this->signatureMethod;
        $params['Timestamp'] = gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
        $params['SellerId'] = $this->sellerId;
        $params['SellerFulfillmentOrderId'] = $this->sellerFulfillmentOrderId;
        $params['MarketplaceId'] = $this->marketplaceId;

        $params['Signature'] = $this->_signParametersAPI($params);

        return $params;
    }

    private function _signParametersAPI($parameters)
    {
//        $parameters['NSFBAHost'] = 'https://amz.luckytail.com/';
        $parameters['SignRequestType'] = 'outbound_service';
        $parameters['AmazonRegionUrl'] = $this->serviceUrl;

        $ch = curl_init('https://fba.atouchpoint.com/api/calculate-mws-signature');
        $payload = json_encode($parameters);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        return $data['signature'];
    }
}