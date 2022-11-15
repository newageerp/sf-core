<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Template;


class ToolbarButtonListWithAction extends ToolbarButton
{
    protected string $path = '';

    protected ?array $extraRequestData = [];
    
    public function getProps(): array
    {
        $oldProps = parent::getProps();

        $props = [];
        $props['button'] = $oldProps;
        $props['actionPath'] = $this->getPath();
        $props['extraRequestData'] = $this->getExtraRequestData();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'buttons.toolbar-button-list-with-action';
    }


    /**
     * Get the value of path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of extraRequestData
     *
     * @return ?array
     */
    public function getExtraRequestData(): ?array
    {
        return $this->extraRequestData;
    }

    /**
     * Set the value of extraRequestData
     *
     * @param ?array $extraRequestData
     *
     * @return self
     */
    public function setExtraRequestData(?array $extraRequestData): self
    {
        $this->extraRequestData = $extraRequestData;

        return $this;
    }
}
