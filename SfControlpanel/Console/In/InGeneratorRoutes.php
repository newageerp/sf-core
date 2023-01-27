<?php

namespace Newageerp\SfControlpanel\Console\In;

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

        $customEmptyTemplate = $twig->load('common/empty.html.twig');

        $generatedRoutesPath = Utils::generatedPath('routes');

        $customRoutesPath = Utils::customFolderPath('routes');

        $imports = [];

        $appsComponents = [];
        if (class_exists('App\Entity\Bookmark', false)) {
            $imports[] = 'import BookmarksPage from "../apps/bookmarks/BookmarksPage";';
            $appsComponents[] = [
                'name' => 'bookmarks',
                'compName' => 'BookmarksPage'
            ];
        }
        if (class_exists('App\Entity\Task', false)) {
            $imports[] = 'import TasksPage from "../apps/tasks/TasksPage";';
            $appsComponents[] = [
                'name' => 'tasks',
                'compName' => 'TasksPage'
            ];
        }
        if (class_exists('App\Entity\Note', false)) {
            $imports[] = 'import NotesPage from "../apps/notes/NotesPage";';
            $appsComponents[] = [
                'name' => 'notes',
                'compName' => 'NotesPage'
            ];
        }
        if (class_exists('App\Entity\FollowUp', false)) {
            $imports[] = 'import FollowUpPage from "../apps/follow-up/FollowUpPage";';
            $appsComponents[] = [
                'name' => 'follow-up',
                'compName' => 'FollowUpPage'
            ];
        }

        $generatedContent = $editRoutesTemplate->render(
            [
                'imports' => $imports,
                'appsComponents' => $appsComponents,
            ]
        );
        $fileName = $generatedRoutesPath . '/AppRoutes.tsx';
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