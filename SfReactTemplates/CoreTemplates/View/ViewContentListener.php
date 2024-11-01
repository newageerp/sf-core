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
    public const MAINVIEWWIDGETRIGHT = 'PageMainViewRightContent';
    public const MAINVIEWWIDGETBOTTOM = 'PageMainViewBottomContent';
    public const MAINVIEWWIDGETMIDDLE = 'PageMainViewMiddleContent';
    public const MAINVIEWWIDGETBOTTOMEXTRA = 'PageMainViewBottomExtraContent';

    public const MAINVIEWTOOLBARAFTER1LINE = 'PageMainViewToolbarAfter1Line';

    public const MAINVIEWTOOLBARAFTERTITLE = 'PageMainViewAfterTitleBlockContent';

    public const MAINVIEWTOOLBARAFTERFIELDS = 'PageMainViewElementToolbarAfterFieldsContent';

    public const MAINVIEWTOOLBARBEFORE2LINE = 'PageMainViewElementToolbarLine2BeforeContent';

    public const MAINVIEWTOOLBARMORE = 'PageMainViewElementToolbarMoreMenuContent';

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
            $requestRecordProvider->setAutoReload(30);

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
            $isCompact = (isset($event->getData()['isCompact']) && $event->getData()['isCompact'])
                || $event->getData()['_isMobile'];

            $widgetTemplateEvents = [
                self::MAINVIEWWIDGETRIGHT => $viewContent->getRightContent(),
                self::MAINVIEWWIDGETBOTTOM => $viewContent->getBottomContent(),
                self::MAINVIEWWIDGETBOTTOMEXTRA => $viewContent->getBottomExtraContent(),
                self::MAINVIEWWIDGETMIDDLE => $viewContent->getMiddleContent(),
                self::MAINVIEWTOOLBARAFTER1LINE => $viewContent->getElementToolbarAfter1Line(),
            ];

            $eventTemplateData = $event->getData();
            $eventTemplateData['viewContent'] = $viewContent;

            foreach ($widgetTemplateEvents as $wdgTemplate => $content) {
                $wEvent = new LoadTemplateEvent(
                    $content,
                    $wdgTemplate,
                    $eventTemplateData,
                );
                $this->eventDispatcher->dispatch($wEvent, LoadTemplateEvent::NAME);
            }

            $afterTitleBlockContentEvent = new LoadTemplateEvent($viewContent->getAfterTitleBlockContent(), self::MAINVIEWTOOLBARAFTERTITLE, $event->getData());
            $this->eventDispatcher->dispatch($afterTitleBlockContentEvent, LoadTemplateEvent::NAME);

            $elementToolbarAfterFieldsContent = new LoadTemplateEvent($viewContent->getElementToolbarAfterFieldsContent(), self::MAINVIEWTOOLBARAFTERFIELDS, $event->getData());
            $this->eventDispatcher->dispatch($elementToolbarAfterFieldsContent, LoadTemplateEvent::NAME);

            $elementToolbarLine2BeforeContent = new LoadTemplateEvent($viewContent->getElementToolbarLine2BeforeContent(), self::MAINVIEWTOOLBARBEFORE2LINE, $event->getData());
            $this->eventDispatcher->dispatch($elementToolbarLine2BeforeContent, LoadTemplateEvent::NAME);

            $elementToolbarMoreMenuContent = new LoadTemplateEvent($viewContent->getElementToolbarMoreMenuContent(), self::MAINVIEWTOOLBARMORE, $event->getData());
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
