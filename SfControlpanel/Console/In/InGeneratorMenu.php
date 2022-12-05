<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Service\MenuService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Symfony\Component\Filesystem\Filesystem;

class InGeneratorMenu extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorMenu';

    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        parent::__construct();
        $this->menuService = $menuService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // NEW
        $url = 'http://local.767.lt:7671/api/projects?populate=deep,7&filters[Slug]=' . $_ENV['SFS_STRAPI_PROJECT'];

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

        // OLD
        $fs = new Filesystem();

        $menuTemplate = file_get_contents(
            __DIR__ . '/templates/menu/MenuItem.txt'
        );
        $menuTitleTemplate = file_get_contents(
            __DIR__ . '/templates/menu/MenuTitle.txt'
        );
        $menuFolderTemplate = file_get_contents(
            __DIR__ . '/templates/menu/MenuFolder.txt'
        );

        $menuFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/menu.json';
        $menuItems = [];
        if (file_exists($menuFile)) {
            $menuItems = json_decode(
                file_get_contents($menuFile),
                true
            );
        }

        $generatedPath = LocalConfigUtils::getFrontendGeneratedPath() . '/menu/items';
        if (!$fs->exists($generatedPath)) {
            $fs->mkdir($generatedPath);
        }

        foreach ($menuItems as $menuItem) {
            $compName = $this->menuService->componentNameForMenu($menuItem);
            $menuLink = $this->menuService->menuLinkForMenu($menuItem);
            $menuTitle = $this->menuService->menuTitleForMenu($menuItem);

            $fileName = $generatedPath . '/' . $compName . '.tsx';
            $localContents = '';
            if ($fs->exists($fileName)) {
                $localContents = file_get_contents($fileName);
            }

            $generatedContent = str_replace(
                [
                    'TP_COMP_NAME',
                    'TP_PATH',
                    'TP_ICON',
                    'TP_TITLE',
                    'TP_BADGE_KEY'
                ],
                [
                    $compName,
                    $menuLink,
                    $menuItem['config']['icon'] ?? '',
                    $menuTitle,
                    $menuItem['config']['badgeKey'] ?? '',
                ],
                $menuTemplate
            );

            if ($localContents !== $generatedContent) {
                file_put_contents(
                    $fileName,
                    $generatedContent
                );
            }
        }

        // MENU TITLE
        $menuTitleFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/menu-title.json';
        $menuTitleItems = [];
        if (file_exists($menuTitleFile)) {
            $menuTitleItems = json_decode(
                file_get_contents($menuTitleFile),
                true
            );
        }

        $generatedPath = LocalConfigUtils::getFrontendGeneratedPath() . '/menu/titles';
        if (!$fs->exists($generatedPath)) {
            $fs->mkdir($generatedPath);
        }

        foreach ($menuTitleItems as $menuItem) {
            $compName = $this->menuService->componentNameForMenuTitle($menuItem);

            $fileName = $generatedPath . '/' . $compName . '.tsx';
            $localContents = '';
            if ($fs->exists($fileName)) {
                $localContents = file_get_contents($fileName);
            }

            $generatedContent = str_replace(
                [
                    'TP_COMP_NAME',
                    'TP_TITLE'
                ],
                [
                    $compName,
                    $menuItem['config']['title']
                ],
                $menuTitleTemplate
            );

            if ($localContents !== $generatedContent) {
                file_put_contents(
                    $fileName,
                    $generatedContent
                );
            }
        }

        // MENU FOLDER
        $menuFolderFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/menu-folder.json';
        $menuFolderItems = [];
        if (file_exists($menuFolderFile)) {
            $menuFolderItems = json_decode(
                file_get_contents($menuFolderFile),
                true
            );
        }

        $generatedPath = LocalConfigUtils::getFrontendGeneratedPath() . '/menu/folders';
        if (!$fs->exists($generatedPath)) {
            $fs->mkdir($generatedPath);
        }

        foreach ($menuFolderItems as $menuItem) {
            $compName = $this->menuService->componentNameForMenuFolder($menuItem);

            $fileName = $generatedPath . '/' . $compName . '.tsx';

            $tpImports = '';
            $tpChilds = '';

            foreach ($menuItem['config']['items'] as $itemId) {
                if (mb_strpos($itemId, 'separator') === 0) {
                    $tpChilds .= "
                <MenuSpacer />";
                } else {
                    foreach ($menuItems as $m) {
                        if ($m['id'] === $itemId) {
                            $menuCompName = $this->menuService->componentNameForMenu($m);

                            $tpChilds .= "
                <" . $menuCompName . " forceSkipIcon={true}/>";

                            $tpImports .= "import " . $menuCompName . " from \"../items/" . $menuCompName . "\" " . PHP_EOL;
                        }
                    }
                }
            }

            $localContents = '';
            if ($fs->exists($fileName)) {
                $localContents = file_get_contents($fileName);
            }

            $generatedContent = str_replace(
                [
                    'TP_COMP_NAME',
                    'TP_TITLE',
                    'TP_ICON',
                    'TP_IMPORTS',
                    'TP_CHILDS'
                ],
                [
                    $compName,
                    $menuItem['config']['title'],
                    $menuItem['config']['icon'],
                    $tpImports,
                    $tpChilds
                ],
                $menuFolderTemplate
            );

            if ($localContents !== $generatedContent) {
                file_put_contents(
                    $fileName,
                    $generatedContent
                );
            }
        }


        return Command::SUCCESS;
    }
}
