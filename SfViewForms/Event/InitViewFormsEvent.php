<?php

namespace Newageerp\SfViewForms\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InitViewFormsEvent extends Event
{
    public const NAME = 'SfViewForms.InitViewFormsEvent';

    protected array $viewForms = [];

    public function __construct(array $viewForms) {
        $this->viewForms = $viewForms;
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

    /**
     * Set the value of viewForms
     *
     * @param array $viewForms
     *
     * @return self
     */
    public function setViewForms(array $viewForms): self
    {
        $this->viewForms = $viewForms;

        return $this;
    }
}
