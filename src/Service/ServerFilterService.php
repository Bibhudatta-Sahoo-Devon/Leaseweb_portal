<?php


namespace App\Service;


use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ServerFilterService
{
    private $ramRepository;
    private $harddiskRepository;
    private $locationRepository;
    public function __construct(RamRepository $ramRepository,HarddiskRepository $harddiskRepository,LocationRepository $locationRepository)
    {
        $this->ramRepository = $ramRepository;
        $this->harddiskRepository = $harddiskRepository;
        $this->locationRepository = $locationRepository;

    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function refactorParameters(Request $request): array
    {
        try {
            $filters['hardDisk']['size']['form'] = $this->memorySizeRefactor($request->query->get('storage_size_from'));
            $filters['hardDisk']['size']['to'] = $this->memorySizeRefactor($request->query->get('storage_size_to'));

            $filters['ramSize'] = $request->query->get('ram_size');

            $filters['hardDisk']['type'] = trim($request->query->get('hdd_type'));
            $filters['location'] = trim($request->query->get('location'));

            if (!empty($filters['ramSize'])){
                foreach ($filters['ramSize'] as $key => $size){
                    $filters['ramSize'][$key] = $this->memorySizeRefactor($size);
                }
            }

            $filterData = [];
            if (!empty($filters)){

                foreach ($filters as $filter => $searchData){

                    switch ($filter){
                        case "hardDisk":
                            $filterData['hddIds'] = $this->harddiskRepository->getHardDiskSearchIds($searchData);
                            break;
                        case "ramSize":
                            $filterData['ramIds'] = $this->ramRepository->getRamSearchIds($searchData);
                            break;
                        case "location":
                            $filterData['locationIds'] = $this->locationRepository->getLocationSearchId($searchData);
                            break;

                    }
                }
            }

            return $filterData;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    /**
     * @param $memorySize
     * @return int
     */
    public function memorySizeRefactor($memorySize): int
    {
        try {
            $memorySize = strtoupper($memorySize);
            $refactorSize =(int)preg_replace("[A-Za-z]", "", $memorySize);

            if (strpos($memorySize, "TB") !== false){
                $refactorSize *=1000;
            }

            return $refactorSize;

        }catch (Exception $exception){
            throw $exception;
        }
    }
}