<?php


namespace App;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiCustomException extends HttpException
{
    public function __construct(int $statusCode, ?string $message = '', \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

}