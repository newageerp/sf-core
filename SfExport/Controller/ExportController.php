<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Newageerp\SfExport\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfUservice\Controller\UControllerBase;
use Newageerp\SfUservice\Service\UService;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Newageerp\SfSocket\Service\SocketService;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfS3Client\SfS3Client;
use Newageerp\SfXlsx\Service\XlsxService;

/**
 * @Route(path="/app/nae-core/export")
 */
class ExportController extends UControllerBase
{
    protected array $headerStyle = [
        'font' => [
            'bold' => true,
        ],
    ];

    protected function applyStyleToRow($sheet, int $row, array $style)
    {
        $sheet->getStyle('A' . $row . ':X' . $row)->applyFromArray($style);
    }

    protected function applyStyleToCell($sheet, string $cell, array $style)
    {
        $sheet->getStyle($cell)->applyFromArray($style);
    }

    /**
     * OaListController constructor.
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);
    }

    /**
     * @Route(path="/doExport")
     * @OA\Post (operationId="NAEUExport")
     */
    public function doExport(Request $request, UService $uService, PropertiesUtilsV3 $propertiesUtilsV3): JsonResponse
    {
        try {
            $exportDir = '/public/export';

            $request = $this->transformJsonBody($request);

            $user = $this->findUser($request);
            if (!$user) {
                throw new Exception('Invalid user');
            }
            AuthService::getInstance()->setUser($user);

            $schema = $request->get('schema');
            $title = $request->get('title');
            $exportOptions = $request->get('exportOptions');
            $fields = $request->get('fields');
            $columns = $request->get('columns');

            $fieldsToReturn = $exportOptions['fieldsToReturn'] ?? ['id'];

            $summary = $exportOptions['summary'] ?? [];
            $filters = $exportOptions['filter'] ?? [];
            $sort = $exportOptions['sort'] ?? [];

            $data = $uService->getListDataForSchema(
                $schema,
                1,
                9999999,
                $fieldsToReturn,
                $filters,
                $exportOptions['extraData'] ?? [],
                $sort,
                $exportOptions['totals'] ?? []
            )['data'];
            $recordsCount = count($data);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', $title);
            $sheet->setCellValue('F1', 'Cols');
            $sheet->setCellValue('G1', count($fieldsToReturn));

            $fileName = $exportDir . '/' . $title . '_' . time() . '.xlsx';

            $this->applyStyleToRow($sheet, 3, $this->headerStyle);

            $parseColumns = $columns ?: array_map(function ($field) use ($schema) {
                $field['path'] = isset($field['relName']) ?
                    $schema . '.' . $field['key'] . '.' . $field['relName'] :
                    $schema . '.' . $field['key'];
                return $field;
            }, $fields);

            $parseColumns = array_map(function ($field) use ($propertiesUtilsV3) {
                $pathArray = explode(".", $field['path']);
                $relName = null;
                if (count($pathArray) === 3) {
                    [$schema, $fieldKey, $relName] = $pathArray;
                } else {
                    [$schema, $fieldKey] = $pathArray;
                }

                $property = $propertiesUtilsV3->getPropertyForSchema($schema, $fieldKey);

                $field['schema'] = $schema;
                $field['fieldKey'] = $fieldKey;
                $field['title'] = isset($field['customTitle']) && $field['customTitle'] ? $field['customTitle'] : $property['title'];
                $field['pivotTitle'] = isset($field['pivotCustomTitle']) ? $field['pivotCustomTitle'] : $field['title'];
                return $field;
            }, $parseColumns);

            $hasPivot = false;
            foreach ($parseColumns as $col) {
                if (isset($col['pivotSetting']) && $col['pivotSetting']) {
                    $hasPivot = true;
                }
            }

            $col = 1;

            foreach ($parseColumns as $field) {
                $pathArray = explode(".", $field['path']);
                $relName = null;
                if (count($pathArray) === 3) {
                    [$schema, $fieldKey, $relName] = $pathArray;
                } else {
                    [$schema, $fieldKey] = $pathArray;
                }

                $title = $field['title'];
                $sheet->setCellValue(XlsxService::getLetters()[$col] . '3', $title);

                if (isset($field['allowEdit']) && $field['allowEdit']) {
                    $sheet->setCellValue(XlsxService::getLetters()[$col] . '2', $fieldKey);

                    $sheet
                        ->getStyle(XlsxService::getLetters()[$col] . '2:' . XlsxService::getLetters()[$col] . ($recordsCount + 3))
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFCFE2F3');
                }
                $col++;
            }
            $row = 4;
            $pivotRow = 0;
            $pivotData = [];
            foreach ($data as $item) {
                $col = 1;
                $pivotCol = 0;
                foreach ($parseColumns as $field) {
                    $pathArray = explode(".", $field['path']);
                    $relName = null;
                    if (count($pathArray) === 3) {
                        [$schema, $fieldKey, $relName] = $pathArray;
                    } else {
                        [$schema, $fieldKey] = $pathArray;
                    }

                    $val = $relName && isset($item[$fieldKey]) && $item[$fieldKey] ?
                        $item[$fieldKey][$relName] :
                        $item[$fieldKey];

                    $prop = $propertiesUtilsV3->getPropertyForSchema($schema, $fieldKey);
                    if ($propertiesUtilsV3->propertyHasEnum($prop)) {
                        $val = $propertiesUtilsV3->getPropertyEnumValue($schema, $fieldKey, $val);
                    }
                    if ($prop) {
                        $naeType = $propertiesUtilsV3->getPropertyNaeType($prop, []);
                        if ($naeType === 'date') {
                            $val = date('Y-m-d', strtotime($val));
                        }
                    }

                    $sheet->getCellByColumnAndRow($col, $row)->setValue($val);

                    if (!isset($pivotData[$pivotRow])) {
                        $pivotData[$pivotRow] = [
                            -1 => '-'
                        ];
                    }
                    $pivotData[$pivotRow][$pivotCol] = $val;

                    $col++;
                    $pivotCol++;
                }
                $row++;
                $pivotRow++;
            }
            XlsxService::autoSizeSheet($sheet);

            // if ($summary) {
            //     $summaryData = $uService->getGroupedListDataForSchema($schema, $filters, $sort, $summary);

            //     $summarySheet = $spreadsheet->createSheet(1);
            //     $summarySheet->setTitle('Summary');

                
            // }

            if ($hasPivot) {
                $pivotRowTitle = '';
                $pivotColTitle = '';
                $pivotTotalTitles = [];
                $pivotTotalIndexes = [];
                $pivotTotalTypes = [];


                $pivotRowIndex = -1;
                $pivotColIndex = -1;

                $pivotSheet = $spreadsheet->createSheet(1);
                $pivotSheet->setTitle('Ataskaita');

                foreach ($parseColumns as $colIndex => $col) {
                    if (isset($col['pivotSetting'])) {
                        if ($col['pivotSetting'] === 'row') {
                            $pivotRowTitle = $col['pivotTitle'];
                            $pivotRowIndex = $colIndex;
                        }
                        if ($col['pivotSetting'] === 'col') {
                            $pivotColTitle = $col['pivotTitle'];
                            $pivotColIndex = $colIndex;
                        }
                        if ($col['pivotSetting'] === 'total' || $col['pivotSetting'] === 'count') {
                            $pivotTotalTitles[] = $col['pivotTitle'];
                            $pivotTotalIndexes[] = $colIndex;
                            $pivotTotalTypes[] = $col['pivotSetting'];
                        }
                    }
                }
                $pivotTotalsCount = count($pivotTotalTitles);

                $pivotSheet->getCellByColumnAndRow(1, 3)->setValue($pivotRowTitle);
                $pivotSheet->getCellByColumnAndRow(2, 1)->setValue($pivotColTitle);

                $this->applyStyleToRow($sheet, 2, $this->headerStyle);
                $this->applyStyleToCell($sheet, 'A3', $this->headerStyle);


                $pivotRowValues = array_values(
                    array_unique(
                        array_map(
                            function ($item) use ($pivotRowIndex) {
                                return $item[$pivotRowIndex];
                            },
                            $pivotData
                        )
                    )
                );

                $pivotColValues = array_values(
                    array_unique(
                        array_map(
                            function ($item) use ($pivotColIndex) {
                                return $item[$pivotColIndex];
                            },
                            $pivotData
                        )
                    )
                );

                foreach ($pivotRowValues as $rowIndex => $rowValue) {
                    $pivotSheet->getCellByColumnAndRow(1, 4 + $rowIndex)->setValue($rowValue);
                }
                foreach ($pivotColValues as $colIndex => $colValue) {
                    $pivotSheet->getCellByColumnAndRow(2 + ($colIndex * $pivotTotalsCount), 2)->setValue($colValue);
                    foreach ($pivotTotalTitles as $totalIndex => $totalTitle) {
                        $pivotSheet->getCellByColumnAndRow(2 + ($colIndex * $pivotTotalsCount) + $totalIndex, 3)->setValue($totalTitle);
                    }
                }

                foreach ($pivotRowValues as $rowIndex => $rowValue) {
                    foreach ($pivotColValues as $colIndex => $colValue) {
                        foreach ($pivotTotalTitles as $totalIndex => $totalTitle) {
                            $colData = array_map(
                                function ($item) use ($totalIndex, $pivotTotalIndexes) {
                                    return $item[$pivotTotalIndexes[$totalIndex]];
                                },
                                array_filter(
                                    $pivotData,
                                    function ($item) use ($pivotRowIndex, $pivotColIndex, $rowValue, $colValue) {
                                        return $item[$pivotRowIndex] === $rowValue && $item[$pivotColIndex] === $colValue;
                                    }
                                )
                            );
                            $val = 0;
                            if ($pivotTotalTypes[$totalIndex] === 'count') {
                                $val = count(array_unique($colData));
                            } else if ($pivotTotalTypes[$totalIndex] === 'total') {
                                $val = array_sum($colData);
                            }
                            $pivotSheet->getCellByColumnAndRow(2 + ($colIndex * $pivotTotalsCount) + $totalIndex, 4 + $rowIndex)->setValue($val);
                        }
                    }
                }
                XlsxService::autoSizeSheet($pivotSheet);
            }

            $url = XlsxService::saveSpreadsheetToFile($spreadsheet, $fileName);

            return $this->json([
                'url' => $url
            ]);
        } catch (Exception $e) {
            $response = $this->json([
                'description' => $e->getMessage(),
                'f' => $e->getFile(),
                'l' => $e->getLine(),
                'fileName' => 'about:blank'

            ]);
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return $response;
        }
    }
}
