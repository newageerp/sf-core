<?php

namespace Newageerp\SfEditForms\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InitEditFormsEvent extends Event
{
    public const NAME = 'SfEditForms.InitEditFormsEvent';

    protected array $editForms = [];

    public function __construct(array $editForms) {
        $this->editForms = $editForms;
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

    /**
     * Set the value of editForms
     *
     * @param array $editForms
     *
     * @return self
     */
    public function setEditForms(array $editForms): self
    {
        $this->editForms = $editForms;

        return $this;
    }
}
