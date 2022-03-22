<?php

namespace App\Controller;

use App\ApiCustomException;
use App\Repository\FiltersRepository;
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
    private $serverRepository;
    private $filterRepository;

    public function __construct(ServerRepository $serverRepository,FiltersRepository $filterRepository)
    {
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

            if (!empty($fileData['serverDetails'])){

                $serverDetails = $fileData['serverDetails'];
                $storeStatus = $this->serverRepository->storeServerDetails($serverDetails['serverData'],$serverDetails['ramData'],$serverDetails['hddData'],$serverDetails['locationData']);

                if ($storeStatus && count($fileData['filterData'])>3){
                    $this->filterRepository->storeFilterDetails($fileData['filterData']);
                    return new JsonResponse(['message'=>'Server details stored successfully'],Response::HTTP_CREATED);
                }
                return new JsonResponse(['message'=>'Server details were already stored successfully'],Response::HTTP_OK);

            }
            return new JsonResponse(['error'=>'Server details not found'],Response::HTTP_BAD_REQUEST);

        }catch (Exception $exception){
            throw new ApiCustomException($exception->getCode(),$exception->getMessage());
        }
    }



}
