<?php

namespace App\Http;

use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHandler
{

  private int $statusCode;
  private string $message;
  private $data;
  private $errorList;

  function __construct(int $statusCode, $message = null, $data = null, $errorList = null)
  {
    $this->statusCode = $statusCode;
    $this->message = $message;
    $this->data = $data;
    $this->errorList = $errorList;
  }

  function response(): JsonResponse
  {
    $date = date(DateTime::ATOM);
    $responseMessage = ($this->message) ? $this->message : Response::$statusTexts[$this->statusCode];

    $response = [
      "timestamp" => $date,
      "code" => $this->statusCode,
      "message" => $responseMessage,
      "data" => $this->data,
    ];

    if ($this->errorList) {
      $response["errors"] = $this->errorList;
    }

    return new JsonResponse($response, $this->statusCode);
  }
}
