<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ServiceException extends HttpException
{
    public function __construct(int $statusCode, string $message)
    {
        parent::__construct($statusCode, $message);
    }
}
