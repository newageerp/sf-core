<?php

namespace Newageerp\SfXlsx\Styles;

use Newageerp\SfXlsx\Styles\StyleInterface;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BgStyle implements StyleInterface
{
    protected string $color = '';

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function applyStyle(Style $style) : void {
        $style->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB($this->color);
    }
}
