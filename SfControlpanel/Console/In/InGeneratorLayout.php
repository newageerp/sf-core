<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Newageerp\SfControlpanel\Service\Entities\EntitiesService;
use Newageerp\SfControlpanel\Service\MenuService;
use Newageerp\SfControlpanel\Service\Properties\PropertyDateService;
use Newageerp\SfControlpanel\Service\Properties\PropertyTotalService;
use Newageerp\SfControlpanel\Service\Tabs\TabsQuickSearchService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InGeneratorLayout extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorLayout';

    protected PropertiesUtilsV3 $propertiesUtilsV3;
    protected EntitiesUtilsV3 $entitiesUtilsV3;
    protected TabsQuickSearchService $tabsQsService;
    protected PropertyDateService $propertyDateService;

    public function __construct(
        PropertiesUtilsV3 $propertiesUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
        TabsQuickSearchService $tabsQsService,
        PropertyDateService $propertyDateService,
    ) {
        parent::__construct();

        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->tabsQsService = $tabsQsService;
        $this->propertyDateService = $propertyDateService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $settings = ConfigService::getConfig('settings');

        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/tmp/smarty',
        ]);

        $widgetsTemplate = $twig->load('layout/generated-widgets.html.twig');
        $widgetComponents = [];

        // WIDGETS

        $fileName = Utils::generatedPath('layout') . '/GeneratedLayoutWidgets.tsx';
        $generatedContent = $widgetsTemplate->render(
            [
                'components' => $widgetComponents
            ]
        );
        Utils::writeOnChanges($fileName, $generatedContent);

        // $hasBookmarks = isset($settings['apps']['bookmarks']) && $settings['apps']['bookmarks'];
        $hasFollowUp = isset($settings['apps']['followUp']) && $settings['apps']['followUp'];

        $hasNotes = isset($settings['apps']['notes']) && $settings['apps']['notes'];
        $hasMails = isset($settings['apps']['mails']) && $settings['apps']['mails'];
        $hasTasks = isset($settings['apps']['tasks']) && $settings['apps']['tasks'];

        $templates = [];
        
        // if ($hasBookmarks) {
        //     $templates['layout/apps/bookmarks/BookmarksPage.html.twig'] = ['apps/bookmarks', 'BookmarksPage'];
        // }
        // if ($hasFollowUp) {
        //     $templates['layout/apps/follow-up/FollowUpPage.html.twig'] = ['apps/follow-up', 'FollowUpPage'];
        // }
        if ($hasNotes || $hasMails) {
            $templates['layout/apps/eventshistory/EventsHistoryWidget.html.twig'] = ['apps/eventshistory', 'EventsHistoryWidget'];
        }
        if ($hasMails) {
            // $templates['layout/apps/mails/MailsContent.html.twig'] = ['apps/mails', 'MailsContent'];
        }
        if ($hasTasks) {
            // $templates['layout/apps/tasks/TasksPage.html.twig'] = ['apps/tasks', 'TasksPage'];
            // $templates['layout/apps/tasks/TasksWidget.html.twig'] = ['apps/tasks', 'TasksWidget'];
        }

        $settings = LocalConfigUtils::getCpConfigFileData('settings');

        foreach ($templates as $template => $target) {
            $fileName = Utils::generatedPath($target[0]) . '/' . $target[1] . '.tsx';
            $generatedContent = $twig->load($template)->render(['hasTasksApp' => $hasTasks, 'hasMailsApp' => $hasMails, 'settings' => $settings]);
            Utils::writeOnChanges($fileName, $generatedContent);
        }

        $templates = [
            'config/fields/onEditElementUpdate.html.twig' => ['fields', 'onEditElementUpdate'],
            'config/fields/fieldDependencies.html.twig' => ['fields', 'fieldDependencies'],
            'config/fields/fieldVisibility.html.twig' => ['fields', 'fieldVisibility'],
            'config/lang/i18.html.twig' => ['lang', 'i18'],

            'config/widgets/widgets/base-entity.widgets.html.twig' => ['widgets/widgets', 'base-entity.widgets'],
            'config/widgets/index.html.twig' => ['widgets', 'index'],
        ];
        foreach ($templates as $template => $target) {
            $fileName = Utils::customFolderPath($target[0]) . '/' . $target[1] . '.tsx';
            if (!file_exists($fileName)) {
                $generatedContent = $twig->load($template)->render([]);
                Utils::writeOnChanges($fileName, $generatedContent);
            }
        }

        // getFrontendModelsCachePath
        $fileName = Utils::customFolderPath('models-cache-data') . '/NotesNameResolver.tsx';
        if (!file_exists($fileName)) {
            $generatedContent = $twig->load('user-components/notes/NotesNameResolver.html.twig')->render();
            Utils::writeOnChanges($fileName, $generatedContent);
        }

        $fileName = Utils::customFolderPath('models-cache-data') . '/types.ts';
        if (!file_exists($fileName)) {
            $generatedContent = $twig->load('user-components/types.html.twig')->render();
            Utils::writeOnChanges($fileName, $generatedContent);
        }

        // ENTITIES
        $entitiesService = new EntitiesService();
        $entitiesService->generate();

        // PROPERTIES
        $propertyTotalService = new PropertyTotalService();
        $propertyTotalService->generate();

        $this->propertyDateService->generate();

        // TABS
        $this->tabsQsService->generate();

        return Command::SUCCESS;
    }
}
