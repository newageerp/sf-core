<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class InlineViewContentListener implements EventSubscriberInterface
{
    protected InlineViewContentService $inlineViewContentService;

    public function __construct(
        InlineViewContentService $inlineViewContentService,
    ) {
        $this->inlineViewContentService = $inlineViewContentService;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('InlineViewContent')) {
            $t = $this->inlineViewContentService->loadView(
                $event->getData()['schema'],
                $event->getData()['type'],
                $event->getData()['id'],
                isset($event->getData()['isCompact']) && $event->getData()['isCompact'],
            );

            $event->getPlaceholder()->addTemplate($t);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
