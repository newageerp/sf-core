<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Edit;

use Newageerp\SfControlpanel\Console\EditFormsUtilsV3;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;

use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\CoreTemplates\Popup\PopupWindow;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfUservice\Service\UService;


class EditContentListener implements EventSubscriberInterface
{
    protected UService $uservice;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EditFormsUtilsV3 $editFormsUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected EditContentService $editContentService;

    public function __construct(
        UService $uservice,
        EntitiesUtilsV3 $entitiesUtilsV3,
        EditFormsUtilsV3 $editFormsUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
        EditContentService $editContentService,
    ) {
        $this->uservice = $uservice;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->editFormsUtilsV3 = $editFormsUtilsV3;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->editContentService = $editContentService;
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
            $isPopup = isset($event->getData()['isPopup']) && $event->getData()['isPopup'];
            $isCompact = isset($event->getData()['isCompact']) && $event->getData()['isCompact'];

            $parentElement = isset($event->getData()['parentElement']) ? $event->getData()['parentElement'] : null;
            $newStateOptions = isset($event->getData()['newStateOptions']) ? $event->getData()['newStateOptions'] : null;

            $editContent->getFormContent()->setIsCompact($isCompact);
            $editContent->getFormContent()->setParentElement($parentElement);
            $editContent->setNewStateOptions($newStateOptions);

            $this->editContentService->fillFormContent(
                $schema,
                $type,
                $editContent->getFormContent(),
                $isCompact
            );

            if ($isPopup) {
                $popupWindow = new PopupWindow();
                $popupWindow->getChildren()->addTemplate($editContent);
                $event->getPlaceholder()->addTemplate($popupWindow);
            } else {
                $event->getPlaceholder()->addTemplate($editContent);

                $toolbarTitle = new MainToolbarTitle($this->entitiesUtilsV3->getTitleBySlug($schema));
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
