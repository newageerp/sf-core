<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Db\RequestRecordProvider;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\CoreTemplates\Toolbar\ToolbarTitle;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfUservice\Service\UService;


class ViewContentListener implements EventSubscriberInterface
{
    protected UService $uservice;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    protected ViewContentService $viewContentService;

    public function __construct(UService $uservice, EntitiesUtilsV3 $entitiesUtilsV3, EventDispatcherInterface $eventDispatcher, ViewContentService $viewContentService)
    {
        $this->uservice = $uservice;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
        $this->viewContentService = $viewContentService;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('PageMainView')) {
            $requestRecordProvider = new RequestRecordProvider(
                $event->getData()['schema'],
                $event->getData()['type'],
                $event->getData()['id'],
            );

            $entity = $this->uservice->getEntityFromSchemaAndId(
                $event->getData()['schema'],
                $event->getData()['id']
            );
            $viewContent = new ViewContent(
                $event->getData()['schema'],
                $event->getData()['type'],
                $event->getData()['id'],
                $entity
            );

            $requestRecordProvider->getChildren()->addTemplate($viewContent);

            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];
            $isCompact = isset($event->getData()['isCompact']) && $event->getData()['isCompact'];

            $rightContentEvent = new LoadTemplateEvent($viewContent->getRightContent(), 'PageMainViewRightContent', $event->getData());
            $this->eventDispatcher->dispatch($rightContentEvent, LoadTemplateEvent::NAME);

            $bottomContentEvent = new LoadTemplateEvent($viewContent->getBottomContent(), 'PageMainViewBottomContent', $event->getData());
            $this->eventDispatcher->dispatch($bottomContentEvent, LoadTemplateEvent::NAME);

            $afterTitleBlockContentEvent = new LoadTemplateEvent($viewContent->getAfterTitleBlockContent(), 'PageMainViewAfterTitleBlockContent', $event->getData());
            $this->eventDispatcher->dispatch($afterTitleBlockContentEvent, LoadTemplateEvent::NAME);

            $elementToolbarAfterFieldsContent = new LoadTemplateEvent($viewContent->getElementToolbarAfterFieldsContent(), 'PageMainViewElementToolbarAfterFieldsContent', $event->getData());
            $this->eventDispatcher->dispatch($elementToolbarAfterFieldsContent, LoadTemplateEvent::NAME);

            $elementToolbarLine2BeforeContent = new LoadTemplateEvent($viewContent->getElementToolbarLine2BeforeContent(), 'PageMainViewElementToolbarLine2BeforeContent', $event->getData());
            $this->eventDispatcher->dispatch($elementToolbarLine2BeforeContent, LoadTemplateEvent::NAME);

            $elementToolbarMoreMenuContent = new LoadTemplateEvent($viewContent->getElementToolbarMoreMenuContent(), 'PageMainViewElementToolbarMoreMenuContent', $event->getData());
            $this->eventDispatcher->dispatch($elementToolbarMoreMenuContent, LoadTemplateEvent::NAME);

            $viewContent->getFormContent()->setIsCompact($isCompact);

            $this->viewContentService->fillFormContent(
                $event->getData()['schema'],
                $event->getData()['type'],
                $viewContent->getFormContent(),
                $isCompact
            );

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->getChildren()->addTemplate($requestRecordProvider);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else {
                $event->getPlaceholder()->addTemplate($requestRecordProvider);

                $toolbarTitle = new MainToolbarTitle($this->entitiesUtilsV3->getTitleBySlug($event->getData()['schema']));
                $event->getPlaceholder()->addTemplate($toolbarTitle);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
