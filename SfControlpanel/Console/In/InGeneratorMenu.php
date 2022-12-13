<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Service\MenuService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Service\Menu\CpMenuService;
use Symfony\Component\Filesystem\Filesystem;

class InGeneratorMenu extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorMenu';

    protected MenuService $menuService;
    protected CpMenuService $cpMenuService;

    public function __construct(MenuService $menuService, CpMenuService $cpMenuService)
    {
        parent::__construct();
        $this->menuService = $menuService;
        $this->cpMenuService = $cpMenuService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cpMenuService->generate();
        return Command::SUCCESS;
    }
}
