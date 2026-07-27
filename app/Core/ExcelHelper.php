<?php

namespace App\Core;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

class ExcelHelper
{
    public static function export(array $headers, array $rows, string $filename = 'export'): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $col = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col++, 1, $header);
        }

        $rowNum = 2;
        foreach ($rows as $row) {
            $col = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($col++, $rowNum, $value);
            }
            $rowNum++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public static function import(string $filepath): array
    {
        if (!file_exists($filepath)) {
            throw new \RuntimeException('File not found: ' . $filepath);
        }
        $reader      = new XlsxReader();
        $spreadsheet = $reader->load($filepath);
        $sheet       = $spreadsheet->getActiveSheet();
        return $sheet->toArray(null, true, true, true);
    }
}
