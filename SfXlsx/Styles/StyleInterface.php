<?php

namespace Newageerp\SfXlsx\Styles;

use PhpOffice\PhpSpreadsheet\Style\Style;

interface StyleInterface
{
    public function applyStyle(Style $style) : void;
}
