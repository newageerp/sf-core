<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Newageerp\SfExport\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfUservice\Controller\UControllerBase;
use Newageerp\SfUservice\Service\UService;
use Newageerp\SfXlsx\Service\SfXlsxExportService;
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
use Newageerp\SfCsv\Service\SfCsvExportService;
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

            $fileType = $exportOptions['fileType'] ?? 'xlsx';

            $fieldsToReturn = $exportOptions['fieldsToReturn'] ?? ['id'];

            $summary = $exportOptions['summary'] ?? [];
            $filters = $exportOptions['filter'] ?? [];
            $sort = $exportOptions['sort'] ?? [];
            $skipMetadata = isset($exportOptions['skipMetaData']) && $exportOptions['skipMetaData'];

            $totals = [];

            /**
             * @var SfXlsxExportService $exportService
             */
            $exportService = null;

            if ($fileType === 'xlsx') {
                $exportService = new SfXlsxExportService();
            } else if ($fileType === 'csv') {
                $exportService = new SfCsvExportService();
            }

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

            $hasEdit = false;
            foreach ($columns as $col) {
                if (isset($col['allowEdit']) && $col['allowEdit']) {
                    $hasEdit = true;
                }
            }

            if (!$hasEdit) {
                $skipMetadata = true;
            }
            if (!$skipMetadata) {
                $startRow = 3;
                $exportService->setCellValue(1, 1, $title);
                $exportService->setCellValue(6, 1, 'Cols');
                $exportService->setCellValue(7, 1, count($fieldsToReturn));
            } else {
                $startRow = 1;
            }

            $fileName = $exportDir . '/' . $title . '_' . time() . '.'.$fileType;

            $exportService->applyStyleToRow($startRow, $this->headerStyle);

            $parseColumns = $columns ?: array_map(function ($field) use ($schema) {
                $field['path'] = isset($field['relName']) ?
                    $schema . '.' . $field['key'] . '.' . $field['relName'] :
                    $schema . '.' . $field['key'];
                return $field;
            }, $fields);

            $parseColumns = array_map(function ($field) use ($propertiesUtilsV3, &$totals) {
                $pathArray = explode(".", $field['path']);
                $relName = null;
                if (count($pathArray) === 3) {
                    [$schema, $fieldKey, $relName] = $pathArray;
                } else {
                    [$schema, $fieldKey] = $pathArray;
                }

                $property = $propertiesUtilsV3->getPropertyForSchema($schema, $fieldKey);

                $naeType = $propertiesUtilsV3->getPropertyNaeType($property, []);

                $totals[$fieldKey] = 0;

                $field['naeType'] = $naeType;
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
                $exportService->setCellValue($col, $startRow, $title);

                if (isset($field['allowEdit']) && $field['allowEdit']) {
                    $exportService->setCellValue($col, 2, $fieldKey);
                    $exportService->setForegroundColor(
                        $col,
                        2,
                        $col,
                        $recordsCount + 3,
                        'FFCFE2F3'
                    );
                }
                $col++;
            }
            $row = 1 + $startRow;
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

                    if ($field['naeType'] === 'float') {
                        $totals[$field['fieldKey']] += $val;
                    }

                    if ($prop) {
                        $naeType = $propertiesUtilsV3->getPropertyNaeType($prop, []);
                        if ($naeType === 'date') {
                            $val = date('Y-m-d', strtotime($val));
                        }
                    }

                    $exportService->setCellValue($col, $row, $val);

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
            $col = 1;
            foreach ($parseColumns as $field) {
                if ($field['naeType'] === 'float') {
                    $exportService->setCellValue($col, $row, round($totals[$field['fieldKey']], 2));
                }
                $col++;
            }
            $exportService->applyStyleToRow($row, $this->headerStyle);
            $exportService->autoSizeSheet();

            foreach ($columns as $colIndex => $col) {
                $letter = XlsxService::getLetters()[$colIndex + 1];
                if (isset($col['settings']['width']) && $col['settings']['width']) {
                    $exportService->setAutoSize($colIndex + 1, false);
                    $exportService->setWidth($colIndex + 1, (int)$col['settings']['width']);
                }
                if (isset($col['settings']['wrapText']) && $col['settings']['wrapText']) {
                    $exportService->setWrapText($colIndex + 1, 1, $colIndex + 1, $row, true);
                }
            }

            if ($hasPivot) {
                $pivotRowTitle = '';
                $pivotColTitle = '';
                $pivotTotalTitles = [];
                $pivotTotalIndexes = [];
                $pivotTotalTypes = [];


                $pivotRowIndex = -1;
                $pivotColIndex = -1;

                $exportService->createPage(1, 'Ataskaita');

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

                $exportService->setCellValue(1, 3, $pivotRowTitle);
                $exportService->setCellValue(2, 1, $pivotColTitle);

                $exportService->applyStyleToRow(2, $this->headerStyle);
                $exportService->applyStyleToCell('A3', $this->headerStyle);


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
                    $exportService->setCellValue(1, 4 + $rowIndex, $rowValue);
                }
                foreach ($pivotColValues as $colIndex => $colValue) {
                    $exportService->setCellValue(2 + ($colIndex * $pivotTotalsCount), 2, $colValue);
                    foreach ($pivotTotalTitles as $totalIndex => $totalTitle) {
                        $exportService->setCellValue(2 + ($colIndex * $pivotTotalsCount) + $totalIndex, 3, $totalTitle);
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
                            $exportService->setCellValue(2 + ($colIndex * $pivotTotalsCount) + $totalIndex, 4 + $rowIndex, $val);
                        }
                    }
                }
                $exportService->autoSizeSheet();
            }

            $url = $exportService->saveToFile($fileName);

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
