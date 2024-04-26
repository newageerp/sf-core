<?php

namespace Newageerp\SfControlpanel\Console;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfEditForms\Event\InitEditFormsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EditFormsUtilsV3
{
    protected array $editForms = [];
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->initEditForms();
    }

    protected function initEditForms()
    {
        $editForms = ConfigService::getConfig('edit', true);
        $ev = new InitEditFormsEvent($editForms);
        $this->eventDispatcher->dispatch($ev, InitEditFormsEvent::NAME);

        $this->editForms = $ev->getEditForms();
    }


    /**
     * Get the value of editForms
     *
     * @return array
     */
    public function getEditForms(): array
    {
        return $this->editForms;
    }

    public function getEditFormBySchemaAndType(string $schema, string $type): ?array
    {
        $formsF = array_filter(
            $this->getEditForms(),
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
