<?php

namespace Newageerp\SfControlpanel\Console\Out;

use Newageerp\SfControlpanel\Service\DocsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OutCacheDocJsonConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:OutCacheDocJson';

    protected DocsService $docsService;

    public function __construct(DocsService $docsService)
    {
        parent::__construct();
        $this->docsService = $docsService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
