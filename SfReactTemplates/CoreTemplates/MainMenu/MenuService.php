<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfReactTemplates\Template\Placeholder;

class MenuService
{
    protected MenuItemFactory $menuItemFactory;

    public function __construct(MenuItemFactory $menuItemFactory)
    {
        $this->menuItemFactory = $menuItemFactory;
    }


    public function parseFolder(string $slug, Placeholder $placeholder)
    {
        $data = LocalConfigUtils::getCpConfigFileData('menu-cache');

        foreach ($data as $item) {
            if ($item['slug'] === $slug) {
                if ($item['design'] === 'Virtual') {
                    $this->parseVirtualFolderContent($item['Content'], $placeholder);
                }
            }
        }
    }

    public function parseVirtualFolderContent(array $content, Placeholder $placeholder)
    {
        foreach ($content as $item) {
            if ($item['__component'] === 'menu.folder') {
                if ($item['menu_folder']['data']['attributes']['Design'] === 'Virtual') {
                    $this->parseVirtualFolderContent(
                        $item['menu_folder']['data']['attributes']['Content'],
                        $placeholder
                    );
                } else {
                    if (isset($item['menu_folder']['data']['attributes']['Content'])) {
                        $placeholder->addTemplate(
                            $this->folderFactory($item['menu_folder']['data']['attributes'], $item['menu_folder']['data']['id'])
                        );
                    }
                }
            }
            if ($item['__component'] === 'menu.title') {
                $menuTitle = new MenuTitle($item['Title']);
                $placeholder->addTemplate($menuTitle);
            }
            if ($item['__component'] === 'menu.menu-item') {
                $menuItem = new MenuItem(
                    $item['Title'],
                    $item['Link'],
                    $item['Icon']
                );

                $placeholder->addTemplate($menuItem);
            }
            if ($item['__component'] === 'menu.menu-item-tab') {
                $placeholder->addTemplate(
                    $this->menuItemFactory->linkForTab(
                        $item['Scheme'],
                        $item['Type'],
                        $item['Icon']
                    )
                );
            }
        }
    }

    public function folderFactory(array $data, ?int $id): MenuFolder
    {
        $folder = new MenuFolder(
            $data['Title'],
            $data['Icon'],
        );
        $folder->setMenuFolderId('folder-' . $id);

        foreach ($data['Content'] as $item) {
            if ($item['__component'] === 'menu.menu-item') {
                $menuItem = new MenuItem(
                    $item['Title'],
                    $item['Link'],
                    $item['Icon']
                );

                $folder->addItem($menuItem);
            }

            if ($item['__component'] === 'menu.menu-item-tab') {
                $folder->addItem(
                    $this->menuItemFactory->linkForTab(
                        $item['Scheme'],
                        $item['Type'],
                        $item['Icon']
                    )
                );
            }
            if ($item['__component'] === 'menu.divider') {
                $folder->addItem(
                    new MenuDivider()
                );
            }
            if ($item['__component'] === 'menu.folder') {
                if (isset($item['menu_folder']['data']['attributes']['Content'])) {
                    $folderAttributes = $item['menu_folder']['data']['attributes'];
                    if (!isset($folderAttributes['Icon']) || !$folderAttributes['Icon']) {
                        $folderAttributes['Icon'] = 'folder';
                    }

                    $subFolder = $this->folderFactory(
                        $folderAttributes,
                        $item['menu_folder']['data']['id']
                    );
                    $subFolder->setContentClassName('tw3-pl-4');
                    $folder->addItem($subFolder);
                }
            }
        }

        return $folder;
    }
}
