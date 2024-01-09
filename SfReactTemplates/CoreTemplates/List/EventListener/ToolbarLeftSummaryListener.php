<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EventListener;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfReactTemplates\CoreTemplates\CustomPluginTemplate;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ToolbarLeftSummaryListener implements EventSubscriberInterface
{

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('TableService.ToolbarLeft')) {
            $entity = $event->getData()['schema'];
            $type = $event->getData()['type'];

            $summaryConfigs = ConfigService::getConfig('summary');

            $summaryConfigs = array_values(
                array_filter(
                    $summaryConfigs,
                    function ($item) use ($entity, $type) {
                        return $item['showInList'] && $item['schema'] === $entity && ($item['type'] === $type || $item['type'] === 'any');
                    }
                )
            );

            if (count($summaryConfigs) > 0) {
                $tpl = new CustomPluginTemplate('_.AppBundle.ListToolbarSummary', [
                    'config' => array_map(
                        function ($item) {
                            return [
                                'id' => $item['id'],
                                'title' => $item['title']
                            ];
                        },
                        $summaryConfigs
                    )
                ]);
                $event->getPlaceholder()->addTemplate($tpl);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => [
                ['onTemplate', 690]
            ]
        ];
    }

}
