<?php

namespace Newageerp\SfXlsx\Styles;

use Newageerp\SfXlsx\Styles\StyleInterface;
use PhpOffice\PhpSpreadsheet\Style\Style;

class TextColorStyle implements StyleInterface
{
    protected string $color = '';

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function applyStyle(Style $style) : void {
        $style->getFont()
        ->getColor()
        ->setARGB($this->color);
    }
}
