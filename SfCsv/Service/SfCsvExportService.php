<?php

namespace Newageerp\SfCsv\Service;

use Newageerp\SfExport\SfExportService;
use Newageerp\SfS3Client\SfS3Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SfCsvExportService extends SfExportService
{
    protected array $data = [];

    public function __construct()
    {
    }

    public function applyStyleToRow($row, $style)
    {
    }

    public function applyStyleToCell($cell, $style)
    {
    }

    public function setCellValue(int $col, int $row, $value)
    {
        if (!isset($this->data[$row])) {
            $this->data[$row] = [];
        }
        $this->data[$row][$col] = $value;
    }

    public function autoSizeSheet()
    {
    }

    public function setForegroundColor(int $startCol, int $startRow, int $finishCol, int $finishRow, $value)
    {
    }

    public function setWrapText(int $startCol, int $startRow, int $finishCol, int $finishRow, bool $val)
    {
    }

    public function setWidth(int $col, int $width)
    {
    }

    public function setAutoSize(int $col, bool $val)
    {
    }

    public function createPage(int $index, ?string $title)
    {
    }

    public function saveToFile(string $fileName)
    {
        $tmpFile = '/tmp/' . time() . '.сым';

        $fp = fopen($tmpFile, 'w');
        foreach ($this->data as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);

        $contents = base64_encode(file_get_contents($tmpFile));
        unlink($tmpFile);
        return SfS3Client::saveBase64File('xlsx/export/tmp/' . $fileName, $contents, 'public-read');
    }
}
