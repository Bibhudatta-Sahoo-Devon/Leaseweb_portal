<?php


namespace App\EventListener;


use App\ApiCustomException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener
{

    public function customExceptionHandler(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $message = sprintf('ERROR :  %s ', $exception->getMessage());

        $response = new JsonResponse();
        $response->setContent($message);


        if ($exception instanceof ApiCustomException) {
            $status = $exception->getStatusCode();

            $status = ($status > 100) ? $status : Response::HTTP_INTERNAL_SERVER_ERROR;

        } elseif ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
        } else {

            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response->setStatusCode($status);

        // sends the modified response object to the event
        $event->setResponse($response);

    }

}