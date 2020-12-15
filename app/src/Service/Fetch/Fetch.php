<?php

namespace App\Service\Fetch;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Fetch implements FetchInterface {

  private $client;

  public function __construct(HttpClientInterface $client)
  {
      $this->client = $client;
  }

  function get(string $path): Array
  {
    $content = [];
    $response = $this->client->request('GET', $path);
    $statusCode = $response->getStatusCode();

    if($statusCode == 200) {
      $content = $response->getContent();
      $content = $response->toArray();
      return $content;
    }

    return $content;
  }
}