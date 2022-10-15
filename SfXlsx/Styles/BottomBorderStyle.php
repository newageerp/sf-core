<?php

namespace Newageerp\SfXlsx\Styles;

use Newageerp\SfXlsx\Styles\StyleInterface;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BottomBorderStyle implements StyleInterface
{
    protected string $color = '';

    public function __construct(string $color)
    {
        $this->color = $color;
    }

    public function applyStyle(Style $style) : void {
        $style->getBorders()
        ->getBottom()
        ->setBorderStyle('thick')
        ->getColor()
        ->setARGB($this->color);
    }
}
