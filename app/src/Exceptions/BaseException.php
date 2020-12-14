<?php

namespace App\Exceptions;

abstract class BaseException extends \RuntimeException implements \Throwable
{
    private Array $errorList;

    public function __construct($message = "It seems that something has gone wrong", int $statusCode = 500, Array $errorList)
    {
        parent::__construct($message, $statusCode);
        $this->errorList = $errorList;
    }

    public function getErrorList() {
        return $this->errorList;
    }
}