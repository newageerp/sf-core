<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfReactTemplates\Template\Template;

class StatusWidgetWithActions extends Template
{
    protected string $entity = '';

    protected string $type = '';

    protected bool $showOnlyActive = false;

    public function __construct(string $entity, string $type)
    {
        $this->entity = $entity;
        $this->type = $type;
    }

    public function getProps(): array
    {
        return [
            'entity' => $this->getEntity(),
            'type' => $this->getType(),
            'showOnlyActive' => $this->getShowOnlyActive(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'view.statuswidgetwithactions';
    }


    /**
     * Get the value of entity
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * Set the value of entity
     *
     * @param string $entity
     *
     * @return self
     */
    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of showOnlyActive
     *
     * @return bool
     */
    public function getShowOnlyActive(): bool
    {
        return $this->showOnlyActive;
    }

    /**
     * Set the value of showOnlyActive
     *
     * @param bool $showOnlyActive
     *
     * @return self
     */
    public function setShowOnlyActive(bool $showOnlyActive): self
    {
        $this->showOnlyActive = $showOnlyActive;

        return $this;
    }
}
