<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InGeneratorLayout extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorLayout';

    protected PropertiesUtilsV3 $propertiesUtilsV3;
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        PropertiesUtilsV3 $propertiesUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
    ) {
        parent::__construct();

        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $settings = ConfigService::getConfig('settings');

        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/tmp/smarty',
        ]);

        $hasMails = isset($settings['apps']['mails']) && $settings['apps']['mails'];
        $hasTasks = isset($settings['apps']['tasks']) && $settings['apps']['tasks'];

        $templates = [];
        
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

        return Command::SUCCESS;
    }
}
