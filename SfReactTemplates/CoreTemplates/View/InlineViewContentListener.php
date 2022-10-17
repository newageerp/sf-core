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


class InlineViewContentListener implements EventSubscriberInterface
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
        if ($event->isTemplateForAnyEntity('InlineViewContent')) {
            $requestRecordProvider = new RequestRecordProvider(
                $event->getData()['schema'],
                $event->getData()['type'],
                $event->getData()['id'],
            );

            // $entity = $this->uservice->getEntityFromSchemaAndId(
            //     $event->getData()['schema'],
            //     $event->getData()['id']
            // );
            
            $isCompact = isset($event->getData()['isCompact']) && $event->getData()['isCompact'];

            $formContent = new ViewFormContent(
                $event->getData()['schema'],
                $event->getData()['type'],
            );
            $requestRecordProvider->getChildren()->addTemplate($formContent);
            $requestRecordProvider->setShowOnEmpty(false);

            $this->viewContentService->fillFormContent(
                $event->getData()['schema'],
                $event->getData()['type'],
                $formContent,
                $isCompact
            );

            $event->getPlaceholder()->addTemplate($requestRecordProvider);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
