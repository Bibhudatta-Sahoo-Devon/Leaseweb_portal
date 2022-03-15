<?php

namespace App\Controller;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\ApiCustomException;
use App\Repository\HarddiskRepository;
use App\Repository\LocationRepository;
use App\Repository\RamRepository;
use App\Repository\ServerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 * @route("/api/search/", name="search.")
 */
class SearchController extends AbstractController
{
    private $ramRepository;
    private $harddiskRepository;
    private $locationRepository;
    private $serverRepository;
    public function __construct(RamRepository $ramRepository,HarddiskRepository $harddiskRepository,LocationRepository $locationRepository, ServerRepository $serverRepository)
    {
        $this->ramRepository = $ramRepository;
        $this->harddiskRepository = $harddiskRepository;
        $this->locationRepository = $locationRepository;
        $this->serverRepository = $serverRepository;

    }

    /**
     * @route("servers/{page<\d+>?1}", name = "server", methods={"GET"})
     * @param Request $request
     * @param int $page
     * @return JsonResponse
     */
    public function searchServer(Request $request, int $page): JsonResponse
    {
        try {
            $parameters = $request->query->all();
            $parameters['page'] = $page+1;
            $nextUrl = $this->generateUrl('search.server', $parameters, UrlGeneratorInterface::ABS_URL);
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

            $servers = $this->serverRepository->searchServerDetails($filterData,$page,$nextUrl);
            return new JsonResponse($servers,Response::HTTP_OK);
        }catch (\Exception $exception){
            throw new ApiCustomException($exception->getCode(),$exception->getMessage());
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
        }catch (\Exception $exception){
            throw $exception;
        }
    }
}
