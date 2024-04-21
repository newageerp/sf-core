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

    protected array $data = [];

    protected int $folderId = 0;

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

    public function parseFile(string $fileName, Placeholder $placeholder)
    {
        $item = json_decode(file_get_contents($fileName), true);

        if ($item && $item['design'] === 'Virtual') {
            $this->parseVirtualFolderContentV2($item['Content'], $placeholder);
        }
    }

    public function parseFolder(string $slug, Placeholder $placeholder)
    {
        $item = $this->findFolderBySlug($slug);

        if ($item && $item['design'] === 'Virtual') {
            $this->parseVirtualFolderContent($item['Content'], $placeholder);
        }
    }


    public function parseVirtualFolderContentV2(array $content, Placeholder $placeholder)
    {
        foreach ($content as $item) {
            if (!isset($item['Icon'])) {
                $item['Icon'] = null;
            }
            if ($item['__component'] === 'menu.folder') {
                if ($item['menu_folder']['Design'] === 'Virtual') {
                    $this->parseVirtualFolderContentV2(
                        $item['menu_folder']['Content'],
                        $placeholder
                    );
                } else {
                    if (isset($item['menu_folder']['Content'])) {
                        $placeholder->addTemplate(
                            $this->folderFactoryV2($item['menu_folder'])
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

    public function parseVirtualFolderContent(array $content, Placeholder $placeholder)
    {
        foreach ($content as $item) {
            if (!isset($item['Icon'])) {
                $item['Icon'] = null;
            }
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

    public function folderFactoryV2(array $data): MenuFolder
    {
        $folder = new MenuFolder(
            $data['Title'],
            $data['Icon'],
        );
        $this->folderId++;
        $folder->setMenuFolderId('folder-' . $this->folderId);
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
                $contentFolder = $item['menu_folder']['Content'];

                if ($contentFolder && isset($contentFolder['Content'])) {
                    if (!isset($contentFolder['Icon']) || !$contentFolder['Icon']) {
                        $contentFolder['Icon'] = 'folder';
                    }

                    $subFolder = $this->folderFactoryV2(
                        $contentFolder
                    );
                    $folder->addItem($subFolder);
                }
            }
        }

        return $folder;
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
