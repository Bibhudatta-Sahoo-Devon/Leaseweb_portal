<?php


namespace App\Service;


use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelFileService
{

    private $ramRepository;
    private $harddiskRepository;
    private $locationRepository;

    public function __construct(RamRepository $ramRepository, HarddiskRepository $harddiskRepository, LocationRepository $locationRepository)
    {
        $this->ramRepository = $ramRepository;
        $this->harddiskRepository = $harddiskRepository;
        $this->locationRepository = $locationRepository;

    }

    /**
     * To process Excel server file
     * @param $file
     * @return array
     * @throws Exception
     */
    public function processServerFile($file): array
    {
        try {

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
                            $filterData[] = [
                                "name" => trim($data['G']),
                                "type" => trim($data['H']),
                                "value" => trim($data['I']),
                            ];
                    }
                    array_shift($filterData);
                }
            }

            $serverDetails = [];

            if (!empty($serverData)) {
                $serverDetails['serverData'] = $serverData;
                $serverDetails['ramData'] = $this->ramRepository->storeRamDetails(array_unique(array_column($serverData, 'ram')));
                $serverDetails['hddData'] = $this->harddiskRepository->storeHardDiskDetails(array_unique(array_column($serverData, 'hdd')));
                $serverDetails['locationData'] = $this->locationRepository->storeLocationDetails(array_unique(array_column($serverData, 'location')));
            }
            return ['serverDetails' => $serverDetails, 'filterData' => $filterData];

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}