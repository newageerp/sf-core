<?php

namespace Newageerp\SfPushMessages\Service;

use Newageerp\SfPushMessages\Interface\IPushMessage;
use Newageerp\SfPushMessages\Interface\IPushService;

class FirebasePushService implements IPushService
{
    protected $server = 'https://fcm.googleapis.com/fcm/send';

    public function sendMessage(IPushMessage $pushMessage, string $token)
    {
        $dataToSend = [
            'data' => $pushMessage->getNotificationData(),
            'notification' => [
                'title' => $pushMessage->getNotificationTitle(),
                'body' => $pushMessage->getNotificationBody()
            ]
        ];

        $fields = [
            'to' => $token,
        ];
        $fields = array_merge($fields, $dataToSend);

        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=' . $_ENV['NAE_SFPUSHMESSAGE_FIREBASE_KEY'],
            'Content-Type: application/json'
        );

        $curlInstance = curl_init();
        curl_setopt($curlInstance, CURLOPT_URL, $this->server);
        curl_setopt($curlInstance, CURLOPT_POST, true);
        curl_setopt($curlInstance, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlInstance, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInstance, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($curlInstance);
        curl_close($curlInstance);

        return $result;
    }
}
