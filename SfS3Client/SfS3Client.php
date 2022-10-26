<?php
namespace Newageerp\SfS3Client;

class SfS3Client {
    public static function saveFile(string $fileName, string $contents, string $acl) {
        $host = 'http://s3client:3000';
        $url = $host . '/upload';

        $ch = curl_init($url);
        $payload = json_encode($exportData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => [
                'name' => $fileName,
                'contents' => $contents,
                'acl' => 'public-read'
            ]
        ]);
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