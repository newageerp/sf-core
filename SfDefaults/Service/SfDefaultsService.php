<?php

namespace Newageerp\SfDefaults\Service;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfDefaults\Event\InitDefaultsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SfDefaultsService
{
    protected EventDispatcherInterface $eventDispatcher;

    protected array $defaults = [];

    public function __construct(
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->initDefaults();
    }

    protected function initDefaults()
    {
        $defaults = ConfigService::getConfig('defaults', true);
        $ev = new InitDefaultsEvent($defaults);
        $this->eventDispatcher->dispatch($ev, InitDefaultsEvent::NAME);
        
        $this->defaults = $ev->getDefaults();
    }


    public function getDefaultsForSchema(string $schema)
    {
        foreach ($this->defaults as $df) {
            if (
                $df['config']['schema'] === $schema
            ) {
                return $df;
            }
        }
        return null;
    }

    public function getQuickSearchForSchema(string $schema)
    {
        $df = $this->getDefaultsForSchema($schema);
        if (
            $df && isset($df['config']['defaultQuickSearch']) &&
            $df['config']['defaultQuickSearch']
        ) {
            return json_decode($df['config']['defaultQuickSearch'], true);
        }
        return [];
    }

    public function getSortForSchema(string $schema)
    {
        $df = $this->getDefaultsForSchema($schema);
        if (
            $df && isset($df['config']['defaultSort']) &&
            $df['config']['defaultSort']
        ) {
            return json_decode($df['config']['defaultSort'], true);
        }
        return [];
    }

    public function isFieldExistsInDefaults(string $schema, string $path)
    {
        $df = $this->getDefaultsForSchema($schema);
        if (!$df || !isset($df['config']['fields'])) {
            return false;
        }
        $founded = array_filter(
            $df['config']['fields'],
            function ($f) use ($path) {
                return $f['path'] === $path;
            }
        );
        return count($founded) > 0;
    }

    /**
     * Get the value of defaults
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }
}
