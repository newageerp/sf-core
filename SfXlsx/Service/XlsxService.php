<?php

namespace Newageerp\SfXlsx\Service;

use Newageerp\SfS3Client\SfS3Client;
use Newageerp\SfXlsx\Styles\StyleArrayInterface;
use Newageerp\SfXlsx\Styles\StyleInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxService
{
    public static function sheetFromArray(array $data, Spreadsheet $spreadsheet, string $title)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($title);

        $row = 1;
        foreach ($data as $rowData) {
            foreach ($rowData as $col => $val) {
                $colIndex = $col + 1;
                $sheet->setCellValue(self::getLetters()[$colIndex] . $row, $val);
            }
            $row++;
        }

        return $sheet;
    }

    public static function saveSpreadsheetToFile(Spreadsheet $spreadsheet, string $fileName)
    {
        $tmpFile = '/tmp/' . time() . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpFile);

        $contents = base64_encode(file_get_contents($tmpFile));
        unlink($tmpFile);
        return SfS3Client::saveBase64File('xlsx/export/tmp/' . $fileName, $contents, 'public-read');
    }

    public static function autoSizeSheet(Worksheet $sheet)
    {
        foreach (self::getLetters() as $letter) {
            if ($letter) {
                $sheet->getColumnDimension($letter)->setAutoSize(true);
            }
        }
    }

    public static function getLetters(): array
    {
        return [
            '',
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'X',
            'Y',
            'Z',
            'AA',
            'AB',
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AH',
            'AI',
            'AJ',
            'AK',
            'AL',
            'AM',
            'AN',
            'AO',
            'AP',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AV',
            'AX',
            'AY',
            'AZ'
        ];
    }

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
