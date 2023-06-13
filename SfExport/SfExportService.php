<?php
namespace Newageerp\SfExport;

abstract class SfExportService {
    abstract public function applyStyleToRow($row, $style);

    abstract public function applyStyleToCell($cell, $style);

    abstract public function setCellValue(int $col, int $row, $value);

    abstract public function autoSizeSheet();

    abstract public function setForegroundColor(int $startCol, int $startRow, int $finishCol, int $finishRow, $value);

    abstract public function setWrapText(int $startCol, int $startRow, int $finishCol, int $finishRow, bool $val);

    abstract public function setWidth(int $col, int $width);

    abstract public function setAutoSize(int $col, bool $val);

    abstract public function createPage(int $index, ?string $title);

    abstract public function saveToFile(string $fileName);
}
