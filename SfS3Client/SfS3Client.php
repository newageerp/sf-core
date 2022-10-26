<?php
namespace Newageerp\SfS3Client;

class SfS3Client {
    public static function saveFile(string $fileName, string $contents, string $acl) {
        $host = 'http://s3client:3000';
        $url = $host . '/upload';

        $ch = curl_init($url);
        $payload = json_encode(
            [
                'file' => [
                    'name' => $fileName,
                    'contents' => $contents,
                    'acl' => $acl
                ]
            ]
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result, true);
        if (isset($json['url'])) {
         return $json['url'];
        }
        return null;
    }

    public static function saveBase64File(string $fileName, string $contents, string $acl) {
        $host = 'http://s3client:3000';
        $url = $host . '/uploadBase64';

        $ch = curl_init($url);
        $payload = json_encode(
            [
                'file' => [
                    'name' => $fileName,
                    'contents' => $contents,
                    'acl' => $acl
                ]
            ]
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result, true);
        if (isset($json['url'])) {
         return $json['url'];
        }
        return null;
    }
}