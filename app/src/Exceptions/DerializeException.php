<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class DerializeException extends BaseException
{
    public function __construct(string $errorMessage)
    {
        parent::__construct($errorMessage, Response::HTTP_BAD_REQUEST);
    }
}