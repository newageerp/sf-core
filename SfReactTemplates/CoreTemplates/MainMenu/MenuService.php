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
        $this->data = LocalConfigUtils::getCpConfigFileData('menu-cache');
    }

    public function findFolderBySlug(string $slug)
    {
        foreach ($this->data as $item) {
            if ($item['slug'] === $slug) {
                return $item;
            }
        }
    }

    public function findFolderById(int $id)
    {
        foreach ($this->data as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
    }

    public function parseFolder(string $slug, Placeholder $placeholder)
    {
        $item = $this->findFolderBySlug($slug);

        if ($item && $item['design'] === 'Virtual') {
            $this->parseVirtualFolderContent($item['Content'], $placeholder);
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
                $folderId = $item['menu_folder']['data']['id'];
                $contentFolder = $this->findFolderById($folderId);
                
                if ($contentFolder && isset($contentFolder['Content'])) {
                    if (!isset($contentFolder['Icon']) || !$contentFolder['Icon']) {
                        $contentFolder['Icon'] = 'folder';
                    }

                    $subFolder = $this->folderFactory(
                        $contentFolder,
                        $contentFolder['id']
                    );
                    $subFolder->setContentClassName('tw3-pl-4');
                    $folder->addItem($subFolder);
                }
            }
        }

        return $folder;
    }
}
