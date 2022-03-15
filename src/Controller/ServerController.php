<?php

namespace App\Controller;

use App\ApiCustomException;
use App\Repository\FiltersRepository;
use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use App\Repository\ServerRepository;
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
     */
    public function storeServerData(): JsonResponse
    {
        try {

            $fileName = 'LeaseWeb_servers_filters_assignment.xlsx';
            $file =  __DIR__ . '/../../public/storage/'.$fileName;
            $fileControllerObj = new FileController();
            $fileData = $fileControllerObj->processServerFile($file);

            if (!empty($fileData['serverData'])){
                $serverData = $fileData['serverData'];
                $filterData = $fileData['filterData'];
                $allRam = array_unique(array_column($serverData,'ram'));
                $allHdd = array_unique(array_column($serverData,'hdd'));
                $allLocation = array_unique(array_column($serverData,'location'));

                $ramData = $this->ramRepository->storeRamDetails($allRam);
                $hddData = $this->harddiskRepository->storeHardDiskDetails($allHdd);
                $locationData = $this->locationRepository->storeLocationDetails($allLocation);
                $storeStatus = $this->serverRepository->storeServerDetails($serverData,$ramData,$hddData,$locationData);

                if ($storeStatus && count($filterData)>3){
                    $this->filterRepository->storeFilterDetails($filterData);
                }

                return new JsonResponse(['message'=>'Server details stored successfully'],Response::HTTP_CREATED);
            }
            return new JsonResponse(['error'=>'Server details not found'],Response::HTTP_BAD_REQUEST);
        }catch (Exception $exception){
            throw new ApiCustomException($exception->getCode(),$exception->getMessage());
        }
    }



}
