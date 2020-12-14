<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends BaseException
{
    public function __construct(Array $errorList)
    {
        parent::__construct("There was an error on validating request parameters", Response::HTTP_BAD_REQUEST, $errorList);
    }
}