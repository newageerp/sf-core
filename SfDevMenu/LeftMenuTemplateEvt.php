<?php

namespace Newageerp\SfDevMenu;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuDivider;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuItem;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuFolder;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuItemFactory;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuTitle;
use Newageerp\SfEntity\Repository\SfExploreDataFolderRepository;
use Newageerp\SfEntity\Repository\SfExploreDataItemRepository;

class LeftMenuTemplateEvt implements EventSubscriberInterface
{
    protected MenuItemFactory $menuItemFactory;
    protected SfExploreDataFolderRepository $exploreDataFolderRepo;
    protected SfExploreDataItemRepository $exploreDataItemRepo;

    public function __construct(
        MenuItemFactory $menuItemFactory,
        SfExploreDataFolderRepository $exploreDataFolderRepo,
        SfExploreDataItemRepository $exploreDataItemRepo,
    ) {
        $this->menuItemFactory = $menuItemFactory;
        $this->exploreDataFolderRepo = $exploreDataFolderRepo;
        $this->exploreDataItemRepo = $exploreDataItemRepo;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        $user = AuthService::getInstance()->getUser();

        if (
            isset($_COOKIE['DEV_MENU']) &&
            $_COOKIE['DEV_MENU'] === 'true' &&
            $event->isTemplateForAnyEntity('App.UserSpaceWrapper.LeftMenu')
        ) {
            $exploreFolders = $this->exploreDataFolderRepo->findBy([], ['sort' => 'ASC', 'title' => 'ASC']);

            if (count($exploreFolders) > 0) {
                // EXPLORE DATA
                $menuTitle = new MenuTitle('Explore');
                $event->getPlaceholder()->addTemplate($menuTitle);

                foreach ($exploreFolders as $f) {
                    /**
                     * @var SfExploreDataItem[] $items
                     */
                    $items = $this->exploreDataItemRepo->findBy(['folder' => $f], ['sort' => 'ASC', 'title' => 'ASC']);
                    $folder = new MenuFolder($f->getTitle());
                    foreach ($items as $it) {
                        $folder->addItem(
                            new MenuItem(
                                $it->getTitle(),
                                '/u/explore/' . $it->getExploreId() . '/list'
                            )
                        );
                    }
                    $event->getPlaceholder()->addTemplate($folder);
                }
            }

            // DEV menu
            $menuTitle = new MenuTitle('DEV menu');
            $event->getPlaceholder()->addTemplate($menuTitle);

            $event->getPlaceholder()->addTemplate(
                new MenuItem('Config', '/c/config')
            );

            $event->getPlaceholder()->addTemplate(
                $this->menuItemFactory->linkForTab('sf-key-value-orm')
            );

            $event->getPlaceholder()->addTemplate(new MenuDivider());

            $event->getPlaceholder()->addTemplate(
                $this->menuItemFactory->linkForTab('sf-explore-data-folder')
            );

            $event->getPlaceholder()->addTemplate(
                $this->menuItemFactory->linkForTab('sf-explore-data-item')
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => ['onTemplate', -256]
        ];
    }
}
