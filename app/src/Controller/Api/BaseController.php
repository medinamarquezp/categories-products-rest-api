<?php

namespace App\Controller\Api;

use App\Http\ResponseHandler;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
  public function success($message = null, $data = null)
  {
    $message = ($message) ? $message : "OK";
    $responseHandler = new ResponseHandler(Response::HTTP_OK, $message, $data);
    return $responseHandler->response();
  }

  public function fail(BaseException $error)
  {
    $errorCode = $error->getCode();
    $errorMessage = $error->getMessage();
    $errorList = $error->getErrorList() ?: [];
    $responseHandler = new ResponseHandler($errorCode, $errorMessage, null, $errorList);
    return $responseHandler->response();
  }
}
