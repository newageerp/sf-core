<?php

namespace Newageerp\SfControlpanel\Console\In;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InLocalConfigSyncEntitiesConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncEntities';

    protected EntityManagerInterface $em;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        EntityManagerInterface $em,
        EntitiesUtilsV3 $entitiesUtilsV3,
    ) {
        parent::__construct();
        $this->em = $em;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        return Command::SUCCESS;
    }
}
