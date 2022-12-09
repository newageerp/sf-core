<?php

namespace Newageerp\SfControlpanel\Service\Menu;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;

class CpMenuService {
    protected $entities = [];

    public function generate() {
        // NEW
        $host = isset($_ENV['STRAPI_URL']) ? $_ENV['STRAPI_URL'] : 'http://192.168.8.117:7671';

        $url = $host.'/api/projects?populate=deep,10&filters[Slug]=' . $_ENV['SFS_STRAPI_PROJECT'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type:application/json',
            'Authorization: bearer 9322cff5ba224e0a774e9ca12fe9ee6eba18c8e6f253d8037ef10538973e621df08e087d986a6903f14f061167dcc5be1aa21ae7e0b21ef8acb23c2265b7b410ff013d0eba4f92f99424819eb1d643cce452682b7221e10d2431855f5e7aa420d8a4dadb7264597d0a6e909294167028b262d0089281349547ae2d65cb8dd25d'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        $menuFolder = array_filter(
            $result['data'][0]['attributes']['menu_folders']['data'],
            function ($item) {
                // return true;
                return isset($item['attributes']) && !!$item['attributes']['Slug'];
            }
        );

        $menuData = array_values(
            array_map(
                function ($item) {
                    $attrs = $item['attributes'];
                    $data = [
                        'design' => $attrs['Design'],
                        'slug' => $attrs['Slug'],
                        'Content' => $attrs['Content']
                    ];

                    return $data;
                },
                $menuFolder
            )
        );

        file_put_contents(
            LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/menu-cache.json',
            json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        return true;
    }
}