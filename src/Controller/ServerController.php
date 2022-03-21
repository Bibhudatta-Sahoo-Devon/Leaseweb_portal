<?php

namespace App\Controller;

use App\ApiCustomException;
use App\Repository\FiltersRepository;
use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use App\Repository\ServerRepository;
use App\Service\ExcelFileService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServerController
 * @package App\Controller
 * @route("/api/server/", name="server.")
 */
class ServerController extends AbstractController
{
    private $ramRepository;
    private $harddiskRepository;
    private $locationRepository;
    private $serverRepository;
    private $filterRepository;

    public function __construct(RamRepository $ramRepository,HarddiskRepository $harddiskRepository,LocationRepository $locationRepository, ServerRepository $serverRepository,FiltersRepository $filterRepository)
    {
        $this->ramRepository = $ramRepository;
        $this->harddiskRepository = $harddiskRepository;
        $this->locationRepository = $locationRepository;
        $this->serverRepository = $serverRepository;
        $this->filterRepository = $filterRepository;

    }


    /**
     * @route("store", name="store_data",methods={"POST"})
     * @param ExcelFileService $excelFileService
     * @return JsonResponse
     */
    public function storeServerData(ExcelFileService $excelFileService): JsonResponse
    {
        try {

            $filePath =  __DIR__ . '/../../public/storage/'.'LeaseWeb_servers_filters_assignment.xlsx';

            $fileData = $excelFileService->processServerFile($filePath);

            if (!empty($fileData['serverData'])){

                $serverData = $fileData['serverData'];

                $ramData = $this->ramRepository->storeRamDetails(array_unique(array_column($serverData,'ram')));
                $hddData = $this->harddiskRepository->storeHardDiskDetails(array_unique(array_column($serverData,'hdd')));
                $locationData = $this->locationRepository->storeLocationDetails(array_unique(array_column($serverData,'location')));

                $storeStatus = $this->serverRepository->storeServerDetails($serverData,$ramData,$hddData,$locationData);

                if ($storeStatus && count($fileData['filterData'])>3){
                    $this->filterRepository->storeFilterDetails($fileData['filterData']);
                }

                return new JsonResponse(['message'=>'Server details stored successfully'],Response::HTTP_CREATED);
            }
            return new JsonResponse(['error'=>'Server details not found'],Response::HTTP_BAD_REQUEST);
        }catch (Exception $exception){
            throw new ApiCustomException($exception->getCode(),$exception->getMessage());
        }
    }



}
