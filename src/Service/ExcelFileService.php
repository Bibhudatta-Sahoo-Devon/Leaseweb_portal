<?php


namespace App\Service;


use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelFileService
{
    /**
     * To process Excel server file
     * @param $file
     * @return array
     * @throws Exception
     */
    public function processServerFile($file): array
    {
        $serverData = $filterData = [];
        if (file_exists($file)) {

            $spreadsheet = IOFactory::load($file);
            $spreadsheet->getActiveSheet()->removeRow(1);
            $rawData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array

            if (!empty($rawData)) {
                foreach ($rawData as $data) {
                    $temp = [
                        'model' => trim($data['A']),
                        'ram' => trim($data['B']),
                        'hdd' => trim($data['C']),
                        'location' => trim($data['D']),
                        'price' => trim($data['E'])
                    ];
                    $serverData[] = $temp;
                    if (!empty(trim($data['G'])))
                        $filterData[] =  [
                            "name" => trim($data['G']),
                            "type"=>trim($data['H']),
                            "value"=>trim($data['I']),
                        ];
                }
                array_shift($filterData);
            }
        }
        return ['serverData'=>$serverData,'filterData'=>$filterData];
    }
}