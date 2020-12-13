<?php

namespace App\Component\Http;

use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse {

  function __construct(int $statusCode) {
    $date = date(DateTime::ATOM);
    $this->response = [
      "timestamp" => $date,
      "statusCode" => $statusCode,
      "statusText" => Response::$statusTexts[$statusCode]
    ];
  }

  function success($data): JsonResponse
  {
    $this->response["data"] = $data;
    return new JsonResponse($this->response, $this->response["statusCode"]);
  }

  function error($error): JsonResponse
  {
    $this->response["error"] = $error;
    return new JsonResponse($this->response, $this->response["statusCode"]);
  }

}