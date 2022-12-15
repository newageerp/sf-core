<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InGeneratorRoutes extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorRoutes';

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    public function __construct(PropertiesUtilsV3 $propertiesUtilsV3)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/tmp',
        ]);

        $editRoutesTemplate = $twig->load('routes/app-routes.html.twig');

        $appRouterTemplate = $twig->load('routes/app-router.html.twig');

        $customEmptyTemplate = $twig->load('common/empty.html.twig');

        $generatedRoutesWrappersPath = Utils::generatedPath('routes/wrappers');
        $generatedRoutesPath = Utils::generatedPath('routes');

        $customRoutesPath = Utils::customFolderPath('routes');

        $tabsFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/tabs.json';
        $tabItems = [];
        if (file_exists($tabsFile)) {
            $tabItems = json_decode(
                file_get_contents($tabsFile),
                true
            );
        }
        $editsFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/edit.json';
        $editItems = [];
        if (file_exists($editsFile)) {
            $editItems = json_decode(
                file_get_contents($editsFile),
                true
            );
        }

        $viewsFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/view.json';
        $viewItems = [];
        if (file_exists($viewsFile)) {
            $viewItems = json_decode(
                file_get_contents($viewsFile),
                true
            );
        }

        $listComponents = [];

        $imports = [];

        foreach ($tabItems as $tabItem) {
            if (isset($tabItem['config']['generateForRel']) && $tabItem['config']['generateForRel']) {
                continue;
            }
            $dataSourceCompName = Utils::fixComponentName(
                ucfirst($tabItem['config']['schema']) .
                ucfirst($tabItem['config']['type']) . 'TableDataSource'
            );
            // $imports[] = 'import ' . $dataSourceCompName . ' from "../tabs/tables-data-source/' . $dataSourceCompName . '"';

            $listComponents[] = [
                'schema' => $tabItem['config']['schema'],
                'type' => $tabItem['config']['type'],
                'compName' => $dataSourceCompName
            ];
        }

        $editComponents = [];
        foreach ($editItems as $editItem) {
            $compNameDataSource = Utils::fixComponentName(
                ucfirst($editItem['config']['schema']) .
                ucfirst($editItem['config']['type']) . 'FormDataSource'
            );
            // $imports[] = 'import ' . $compNameDataSource . ' from "../editforms/forms-data-source/' . $compNameDataSource . '"';

            $editComponents[] = [
                'schema' => $editItem['config']['schema'],
                'type' => $editItem['config']['type'],
                'compName' => $compNameDataSource
            ];
        }

        $viewComponents = [];
        foreach ($viewItems as $viewItem) {
            $generateForWidget = isset($viewItem['config']['generateForWidget']) && $viewItem['config']['generateForWidget'];
            if (!$generateForWidget) {
//            $compNameDataSource = Utils::fixComponentName(
//                ucfirst($editItem['config']['schema']) .
//                ucfirst($editItem['config']['type']) . 'FormDataSource'
//            );
//            $imports[] = 'import ' . $compNameDataSource . ' from "../editforms/forms-data-source/' . $compNameDataSource . '"';

                $viewComponents[] = [
                    'schema' => $viewItem['config']['schema'],
                    'type' => $viewItem['config']['type'],
//                'compName' => $compNameDataSource
                ];
            }
        }

        $appsComponents = [];
        if (class_exists('App\Entity\Bookmark')) {
            $imports[] = 'import BookmarksPage from "../apps/bookmarks/BookmarksPage";';
            $appsComponents[] = [
                'name' => 'bookmarks',
                'compName' => 'BookmarksPage'
            ];
        }
        if (class_exists('App\Entity\Task')) {
            $imports[] = 'import TasksPage from "../apps/tasks/TasksPage";';
            $appsComponents[] = [
                'name' => 'tasks',
                'compName' => 'TasksPage'
            ];
        }
        if (class_exists('App\Entity\Note')) {
            $imports[] = 'import NotesPage from "../apps/notes/NotesPage";';
            $appsComponents[] = [
                'name' => 'notes',
                'compName' => 'NotesPage'
            ];
        }
        if (class_exists('App\Entity\FollowUp')) {
            $imports[] = 'import FollowUpPage from "../apps/follow-up/FollowUpPage";';
            $appsComponents[] = [
                'name' => 'follow-up',
                'compName' => 'FollowUpPage'
            ];
        }

        $generatedContent = $editRoutesTemplate->render(
            [
                'imports' => $imports,
                'listComponents' => $listComponents,
                'editComponents' => $editComponents,
                'viewComponents' => $viewComponents,
                'appsComponents' => $appsComponents,
            ]
        );
        $fileName = $generatedRoutesPath . '/AppRoutes.tsx';
        Utils::writeOnChanges($fileName, $generatedContent);

        // AppRouter
        $generatedContent = $appRouterTemplate->render();
        $fileName = $generatedRoutesWrappersPath . '/AppRouter.tsx';
        Utils::writeOnChanges($fileName, $generatedContent);

        // Custom toolbar
        $customLayoutPath = Utils::customFolderPath('layout');

        $customFileName = $customLayoutPath . '/CustomToolbarBefore.tsx';
        if (!file_exists($customFileName)) {
            $generatedContent = $customEmptyTemplate->render(['compName' => 'CustomToolbarBefore']);
            Utils::writeOnChanges($customFileName, $generatedContent);
        }
        $customFileName = $customLayoutPath . '/CustomToolbarAfter.tsx';
        if (!file_exists($customFileName)) {
            $generatedContent = $customEmptyTemplate->render(['compName' => 'CustomToolbarAfter']);
            Utils::writeOnChanges($customFileName, $generatedContent);
        }

        // CustomUserWrapperRoutes
        $fileName = $customRoutesPath . '/CustomUserWrapperRoutes.tsx';
        if (!file_exists($fileName)) {
            $generatedContent = $twig->load('routes/CustomUserWrapperRoutes.html.twig')->render();
            Utils::writeOnChanges($fileName, $generatedContent);
        }
        return Command::SUCCESS;
    }
}