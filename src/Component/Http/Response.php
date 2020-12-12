<?php

namespace App\Component\Http;

use DateTime;
use Symfony\Component\Serializer\Serializer;

class Response {
  private int $statusCode;
  private string $message;
  private Array $data;
  private DateTime $timestamp;

  function __construct(int $statusCode, string $message, Array $data) {
    $this->statusCode = $statusCode;
    $this->message = $message;
    $this->data = $data;
    $this->timestamp = new DateTime();
  }

  function getResponse(Serializer $serializer, string $format = "json")
  {
    return $serializer()->serialize($this, $format);
  }

}