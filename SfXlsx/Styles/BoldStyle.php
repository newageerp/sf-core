<?php
namespace Newageerp\SfXlsx\Styles;
use PhpOffice\PhpSpreadsheet\Style\Style;

class BoldStyle implements StyleInterface {
    public function getStyle(): array {
        return [
            'font' => [
                'bold' => true,
            ],
        ];
    }

    public function applyStyle(Style $style) : void {

    }
}