<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Edit;

use Newageerp\SfControlpanel\Console\EditFormsUtilsV3;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;

use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfUservice\Service\UService;


class EditContentListener implements EventSubscriberInterface
{
    public const MAINEDITRIGHT = 'EditContentListenerRight';

    protected UService $uservice;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EditFormsUtilsV3 $editFormsUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected EditContentService $editContentService;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UService $uservice,
        EntitiesUtilsV3 $entitiesUtilsV3,
        EditFormsUtilsV3 $editFormsUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
        EditContentService $editContentService,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->uservice = $uservice;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->editFormsUtilsV3 = $editFormsUtilsV3;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->editContentService = $editContentService;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('PageMainEdit')) {
            $schema = $event->getData()['schema'];
            $type = isset($event->getData()['type']) && $event->getData()['type'] ? $event->getData()['type'] : 'main';

            $id = $event->getData()['id'] === 'new' ? 0 : $event->getData()['id'];

            $entity = $this->uservice->getEntityFromSchemaAndId(
                $schema,
                $id
            );
            $editContent = new EditContent(
                $schema,
                $type,
                $event->getData()['id'],
                $entity
            );

            $widgetTemplateEvents = [
                self::MAINEDITRIGHT => $editContent->getRightContent(),
            ];
            $eventTemplateData = $event->getData();
            $eventTemplateData['editContent'] = $editContent;

            foreach ($widgetTemplateEvents as $wdgTemplate => $content) {
                $wEvent = new LoadTemplateEvent(
                    $content,
                    $wdgTemplate,
                    $eventTemplateData,
                );
                $this->eventDispatcher->dispatch($wEvent, LoadTemplateEvent::NAME);
            }

            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];
            $isCompact = (isset($event->getData()['isCompact']) && $event->getData()['isCompact']) || $event->getData()['_isMobile'];

            $parentElement = isset($event->getData()['parentElement']) ? $event->getData()['parentElement'] : null;
            $newStateOptions = isset($event->getData()['newStateOptions']) ? $event->getData()['newStateOptions'] : null;

            $editContent->getFormContent()->setIsCompact($isCompact);
            $editContent->getFormContent()->setParentElement($parentElement);
            $editContent->setNewStateOptions($newStateOptions);

            $result = $this->editContentService->fillFormContent(
                $schema,
                $type,
                $editContent->getFormContent(),
                $isCompact
            );
            if (isset($result['requiredFields'])) {
                $editContent->setRequiredFields($result['requiredFields']);
            }

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->setClassName('min-w-[50vw]');
                $popupWindow->getChildren()->addTemplate($editContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else {
                $event->getPlaceholder()->addTemplate($editContent);
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
