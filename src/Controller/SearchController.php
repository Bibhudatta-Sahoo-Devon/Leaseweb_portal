<?php

namespace App\Controller;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\ApiCustomException;
use App\Repository\ServerRepository;
use App\Service\ServerFilterService;
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
    private $serverRepository;
    private $serverFilterService;
    public function __construct(ServerRepository $serverRepository, ServerFilterService $serverFilterService)
    {
        $this->serverRepository = $serverRepository;
        $this->serverFilterService = $serverFilterService;
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
            $filterData = $this->serverFilterService->refactorParameters($request);

            $parameters = $request->query->all();
            $parameters['page'] = $page+1;
            $nextUrl = $this->generateUrl('search.server', $parameters, UrlGeneratorInterface::ABS_URL);

            $servers = $this->serverRepository->searchServerDetails($filterData,$page,$nextUrl);

            return new JsonResponse($servers,Response::HTTP_OK);
        }catch (\Exception $exception){
            throw new ApiCustomException($exception->getCode(),$exception->getMessage());
        }
    }



}
