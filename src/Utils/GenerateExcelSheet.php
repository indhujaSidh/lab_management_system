<?php

namespace App\Utils;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\JsonResponse;


class GenerateExcelSheet
{
    /**
     * excelFormatter constructor.
     */
    public function __construct()
    {


    }

    public function getFormattedExcelSheet(array $attr)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle($attr['title']);
        $sheet = $spreadsheet->getSheet(0);
        $sheet->setTitle($attr['title']);
        $columnHeaders = $attr['columnsHeaders'];
        $headerCell = 'A5';
        foreach ($columnHeaders as $index => $header) {
            $cell = '';
            $current = $index;
            while ($current >= 0) {
                $cell = chr(65 + $current % 26) . $cell;
                $current = (int)($current / 26) - 1;
            }
            $sheet->getColumnDimension($cell)->setWidth(18);
            $cell .= '5';
            $sheet->setCellValue($cell, $header);
            $headerCell = $cell;
        }

        $sheet->getTabColor()->setRGB('D9EAD3');
        $headerRange = 'A5:' . $headerCell;
        $sheet->getStyle($headerRange)->getAlignment()->setWrapText(true);
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFont()->getColor()->setRGB('741B47');
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');

        if (isset($attr['hideColumn'])) {
            foreach ($attr['hideColumn'] as $hc)
            {
                $column = $sheet->getColumnDimension($hc);
                $column->setVisible(false);
            }

        }

        $rowIndex = 6;
        foreach ($attr['data'] as $row) {
            $colIndex = 'A';
            foreach ($row as $value) {
                $cell = $sheet->getCell($colIndex . $rowIndex);
                $cell->setValue($value);
                if (in_array($colIndex, $attr['alignRight'])) {
                    $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                } elseif (in_array($colIndex, $attr['alignLeft'])) {
                    $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } elseif (in_array($colIndex, $attr['alignCenter'])) {
                    $cell->getStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }

                if (in_array($colIndex, $attr['currencyColumn'])) {
                    $cell->getStyle()->getNumberFormat()->setFormatCode('#,##0.00 LKR');
                }

                if (isset($attr['percentageColumn'])) {
                    if (in_array($colIndex, $attr['percentageColumn'])) {
                        $roundedValue = round($value);
                        $cell->getStyle()->getNumberFormat()->setFormatCode('0%');
                        $cell->setValue($roundedValue / 100);
                    }
                }
                if (isset($attr['booleanValueColumn'])) {
                    if (in_array($colIndex, $attr['booleanValueColumn'])) {
                        if($value === true)
                        {
                            $cell->setValue('yes');
                        }else{
                            $cell->setValue('No');
                        }
                    }
                }

                $colIndex++;
            }
            $rowIndex++;
        }


        $rowCount = $sheet->getHighestDataRow();// Get the highest row number with data
        $height = 20;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $rowNumber = 5;
        $lastColumn = $sheet->getHighestColumn($rowNumber);
        for ($row = 5; $row <= $rowCount; $row++) {
            $cellRange = 'A' . $row . ':' . $lastColumn . $row;
            $sheet->getRowDimension($row)->setRowHeight($height);
            $sheet->getStyle($cellRange)->applyFromArray($styleArray);
        }

        $sheet->getRowDimension(5)->setRowHeight($attr['height']);

        //Sheet heading and styles
        $sheet->setCellValue('A2', 'AUTOMICLAB');
        $sheet->setCellValue('A3', $attr['title']);
        $sheet->mergeCells('A2:' . $lastColumn . '2');
        $sheet->mergeCells('A3:' . $lastColumn . '3');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->getColor()->setRGB('741B47');
        $sheet->getStyle('A2')->getFont()->setSize(16);
        $sheet->getStyle('A3')->getFont()->getColor()->setRGB('6B6E6C');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        $writer = new Xlsx($spreadsheet);
        $writer->save('../reports/backend_reports/' . $attr['title'] . '.xlsx');

        return new JsonResponse('success');

    }


}