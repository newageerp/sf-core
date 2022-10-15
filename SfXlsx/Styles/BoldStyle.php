<?php
namespace Newageerp\SfXlsx\Styles;

class BoldStyle implements StyleInterface {
    public function getStyle(): array {
        return [
            'font' => [
                'bold' => true,
            ],
        ];
    }
}