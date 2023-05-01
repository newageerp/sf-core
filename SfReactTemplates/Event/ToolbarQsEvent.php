<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarQs;
use Symfony\Contracts\EventDispatcher\Event;

class ToolbarQsEvent extends Event
{
    public const NAME = 'sfreacttemplates.ToolbarQsEvent';

    protected ToolbarQs $toolbarQs;

    public function __construct(
        ToolbarQs $toolbarQs,
    ) {
        $this->toolbarQs = $toolbarQs;
    }


    /**
     * Get the value of toolbarQs
     *
     * @return ToolbarQs
     */
    public function getToolbarQs(): ToolbarQs
    {
        return $this->toolbarQs;
    }

    /**
     * Set the value of toolbarQs
     *
     * @param ToolbarQs $toolbarQs
     *
     * @return self
     */
    public function setToolbarQs(ToolbarQs $toolbarQs): self
    {
        $this->toolbarQs = $toolbarQs;

        return $this;
    }
}
