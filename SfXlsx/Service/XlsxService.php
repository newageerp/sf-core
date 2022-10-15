<?php
namespace Newageerp\SfXlsx\Service;

use Newageerp\SfXlsx\Styles\StyleArrayInterface;
use Newageerp\SfXlsx\Styles\StyleInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsxService {
    public function applyStyleFromArrayToRow(
        StyleArrayInterface $style, 
        int $row, 
        Worksheet $sheet
    ) {
        $sheet->getStyle('A' . $row . ':X' . $row)->applyFromArray($style->getStyle());
    }

    public function applyStyleToRow(
        StyleInterface $style, 
        int $row, 
        Worksheet $sheet
    ) {
        $style->applyStyle($sheet->getStyle('A' . $row . ':X' . $row));
    }
}