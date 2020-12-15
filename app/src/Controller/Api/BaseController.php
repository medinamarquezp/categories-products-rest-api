<?php

namespace App\Controller\Api;

use App\Http\ResponseHandler;
use App\Exceptions\BaseException;
use App\Exceptions\DerializeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController extends AbstractController
{
  private $serializer;

  public function __construct(SerializerInterface $serializer)
  {
    $this->serializer = $serializer;
  }

  public function deserialize(Request $request, $entity)
  {
    try {
      $content = $request->getContent();
      if(empty($content)) throw new DerializeException("Request body can not be empty");

      $deserializedEntity = $this->serializer->deserialize($content, $entity, 'json');
      return $deserializedEntity;
    } catch (BaseException $ex) {
      throw $ex;
    }
  }

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
