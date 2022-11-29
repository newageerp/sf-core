<?php

namespace Newageerp\SfControlpanel\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class DocsService
{
    protected KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getDocJson()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'nelmio:apidoc:dump',
            '--format' => 'json',
            '-v'
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput(BufferedOutput::VERBOSITY_QUIET);
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();
        $contentJson = json_decode($content, true);

        return $contentJson;
    }
}
