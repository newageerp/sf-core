<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfReactTemplates\Event\MenuItemTabParseEvent;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MenuService
{
    protected MenuItemFactory $menuItemFactory;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        MenuItemFactory $menuItemFactory,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->menuItemFactory = $menuItemFactory;
        $this->eventDispatcher = $eventDispatcher;
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
                $event = new MenuItemTabParseEvent($item['Scheme'], $item['Type'], $item['Icon']);
                $this->eventDispatcher->dispatch($event, MenuItemTabParseEvent::NAME);

                if ($event->getEnable()) {
                    $placeholder->addTemplate(
                        $this->menuItemFactory->linkForTab(
                            $event->getSchema(),
                            $event->getType(),
                            $event->getIcon(),
                        )
                    );
                }
            }
            if ($item['__component'] === 'menu.divider') {
                $placeholder->addTemplate(
                    new MenuDivider()
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
        $folder->setContentClassName('pl-2');

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
                $event = new MenuItemTabParseEvent($item['Scheme'], $item['Type'], $item['Icon']);
                $this->eventDispatcher->dispatch($event, MenuItemTabParseEvent::NAME);

                if ($event->getEnable()) {
                    $folder->addItem(
                        $this->menuItemFactory->linkForTab(
                            $event->getSchema(),
                            $event->getType(),
                            $event->getIcon()
                        )
                    );
                }
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
                    $folder->addItem($subFolder);
                }
            }
        }

        return $folder;
    }
}
