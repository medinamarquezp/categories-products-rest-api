<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends BaseException
{
    public function __construct(string $errorMessage)
    {
        parent::__construct($errorMessage, Response::HTTP_NOT_FOUND);
    }
}