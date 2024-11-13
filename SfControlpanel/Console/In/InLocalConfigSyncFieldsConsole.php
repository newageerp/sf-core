<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InLocalConfigSyncFieldsConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncFields';

    protected EntityManagerInterface $em;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        EntityManagerInterface $em,
        PropertiesUtilsV3        $propertiesUtilsV3,
        EntitiesUtilsV3          $entitiesUtilsV3,
    ) {
        parent::__construct();
        $this->em = $em;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
