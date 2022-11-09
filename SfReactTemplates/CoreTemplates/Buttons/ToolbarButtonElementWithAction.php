<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Template;


class ToolbarButtonElementWithAction extends ToolbarButton
{
    protected string $path = '';
    
    public function getProps(): array
    {
        $oldProps = parent::getProps();

        $props = [];
        $props['button'] = $oldProps;
        $props['actionPath'] = $this->getPath();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'buttons.toolbar-button-element-with-action';
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
}
