<?php

namespace Newageerp\SfControlpanel\Console;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfViewForms\Event\InitViewFormsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ViewFormsUtilsV3
{
    protected array $viewForms = [];
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->initViewForms();
    }

    protected function initViewForms()
    {
        $editForms = ConfigService::getConfig('view', true);
        $ev = new InitViewFormsEvent($editForms);
        $this->eventDispatcher->dispatch($ev, InitViewFormsEvent::NAME);

        $this->viewForms = $ev->getViewForms();
    }

    /**
     * Get the value of viewForms
     *
     * @return array
     */
    public function getViewForms(): array
    {
        return $this->viewForms;
    }

    public function getViewFormBySchemaAndType(string $schema, string $type): ?array
    {
        $formsF = array_filter(
            $this->getViewForms(),
            function ($item) use ($schema, $type) {
                return $item['config']['schema'] === $schema && $item['config']['type'] === $type;
            }
        );
        if (count($formsF) > 0) {
            return reset($formsF)['config'];
        }
        return null;
    }
}
