<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class FreeBgBadgeWidget extends Template
{
    protected string $bgColor = '';

    protected string $text = '';

    public function __construct(string $bgColor, string $text)
    {
        $this->bgColor = $bgColor;
        $this->text = $text;
    }

    public function getProps(): array
    {
        return [
            'bgColor' => $this->getBgColor(),
            'text' => $this->getText(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'widgets.freebgbadgewidget';
    }


    /**
     * Get the value of bgColor
     *
     * @return string
     */
    public function getBgColor(): string
    {
        return $this->bgColor;
    }

    /**
     * Set the value of bgColor
     *
     * @param string $bgColor
     *
     * @return self
     */
    public function setBgColor(string $bgColor): self
    {
        $this->bgColor = $bgColor;

        return $this;
    }

    /**
     * Get the value of text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @param string $text
     *
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
