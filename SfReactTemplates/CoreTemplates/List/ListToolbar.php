<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListToolbar extends Template
{
    protected Placeholder $toolbarLeft;

    protected Placeholder $toolbarRight;

    protected Placeholder $toolbarMiddle;

    protected string $dataSource = '';

    public function __construct(string $dataSource)
    {
        $this->dataSource = $dataSource;

        $this->toolbarLeft = new Placeholder();
        $this->toolbarRight = new Placeholder();
        $this->toolbarMiddle = new Placeholder();
    }

    public function hasTemplates()
    {
        return (count($this->getToolbarLeft()->getTemplates()) > 0 ||
            count($this->getToolbarRight()->getTemplates()) > 0 ||
            count($this->getToolbarMiddle()->getTemplates()) > 0);
    }

    public function getProps(): array
    {
        return [
            'toolbarLeft' => $this->getToolbarLeft()->toArray(),
            'toolbarRight' => $this->getToolbarRight()->toArray(),
            'toolbarMiddle' => $this->getToolbarMiddle()->toArray(),
            'dataSource' => $this->getDataSource(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.ListToolbar';
    }

    /**
     * Get the value of toolbarLeft
     *
     * @return Placeholder
     */
    public function getToolbarLeft(): Placeholder
    {
        return $this->toolbarLeft;
    }

    /**
     * Set the value of toolbarLeft
     *
     * @param Placeholder $toolbarLeft
     *
     * @return self
     */
    public function setToolbarLeft(Placeholder $toolbarLeft): self
    {
        $this->toolbarLeft = $toolbarLeft;

        return $this;
    }

    /**
     * Get the value of toolbarRight
     *
     * @return Placeholder
     */
    public function getToolbarRight(): Placeholder
    {
        return $this->toolbarRight;
    }

    /**
     * Set the value of toolbarRight
     *
     * @param Placeholder $toolbarRight
     *
     * @return self
     */
    public function setToolbarRight(Placeholder $toolbarRight): self
    {
        $this->toolbarRight = $toolbarRight;

        return $this;
    }

    /**
     * Get the value of toolbarMiddle
     *
     * @return Placeholder
     */
    public function getToolbarMiddle(): Placeholder
    {
        return $this->toolbarMiddle;
    }

    /**
     * Set the value of toolbarMiddle
     *
     * @param Placeholder $toolbarMiddle
     *
     * @return self
     */
    public function setToolbarMiddle(Placeholder $toolbarMiddle): self
    {
        $this->toolbarMiddle = $toolbarMiddle;

        return $this;
    }

    /**
     * Get the value of dataSource
     *
     * @return string
     */
    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    /**
     * Set the value of dataSource
     *
     * @param string $dataSource
     *
     * @return self
     */
    public function setDataSource(string $dataSource): self
    {
        $this->dataSource = $dataSource;

        return $this;
    }
}
