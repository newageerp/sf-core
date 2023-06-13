<?php

namespace Newageerp\SfXlsx\Service;

use Newageerp\SfExport\SfExportService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SfXlsxExportService extends SfExportService
{
    protected Spreadsheet $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function applyStyleToRow($row, $style)
    {
        $this->spreadsheet->getActiveSheet()->getStyle('A' . $row . ':X' . $row)->applyFromArray($style);
    }

    public function applyStyleToCell($cell, $style)
    {
        $this->spreadsheet->getActiveSheet()->getStyle($cell)->applyFromArray($style);
    }

    public function setCellValue(int $col, int $row, $value)
    {
        $this->spreadsheet->getActiveSheet()->setCellValue(XlsxService::getLetters()[$col] . '' . $row, $value);
    }

    public function autoSizeSheet()
    {
        XlsxService::autoSizeSheet($this->spreadsheet->getActiveSheet());
    }

    public function setForegroundColor(int $startCol, int $startRow, int $finishCol, int $finishRow, $value)
    {
        $this->spreadsheet->getActiveSheet()
            ->getStyle(XlsxService::getLetters()[$startCol] . '' . $startRow . ':' . XlsxService::getLetters()[$finishCol] . '' . $finishRow)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($value);
    }

    public function setWrapText(int $startCol, int $startRow, int $finishCol, int $finishRow, bool $val)
    {
        $this->spreadsheet->getActiveSheet()
            ->getStyle(XlsxService::getLetters()[$startCol] . '' . $startRow . ':' . XlsxService::getLetters()[$finishCol] . '' . $val)
            ->getAlignment()
            ->setWrapText(true);
    }

    public function setWidth(int $col, int $width)
    {
        $this->spreadsheet->getActiveSheet()->getColumnDimension(XlsxService::getLetters()[$col])->setWidth((int)$width, 'px');
    }

    public function setAutoSize(int $col, bool $val)
    {
        $this->spreadsheet->getActiveSheet()->getColumnDimension(XlsxService::getLetters()[$col])->setAutoSize($val);
    }

    public function createPage(int $index, ?string $title)
    {
        $sheet = $this->spreadsheet->createSheet($index);
        if ($title) {
            $sheet->setTitle($title);
        }
        $this->spreadsheet->setActiveSheetIndex($index);
        return $sheet;
    }

    public function saveToFile(string $fileName)
    {
        return XlsxService::saveSpreadsheetToFile($this->spreadsheet, $fileName);
    }
}
