<?php

namespace App\Service\ExchangeRate;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRate implements ExchangeRateInterface {

  private $client;

  public function __construct(HttpClientInterface $client)
  {
      $this->client = $client;
  }

  function fetch(string $baseCurrency, string $newCurrency): float
  {
    $rate = 1;
    $apiURL = "https://api.exchangeratesapi.io/latest?base=" . $baseCurrency . "&symbols=" . $newCurrency;
    $response = $this->client->request('GET', $apiURL);
    $statusCode = $response->getStatusCode();

    if($statusCode == 200) {
      $content = $response->getContent();
      $content = $response->toArray();
      $rate = $content["rates"][$newCurrency];
    }

    return $rate;
  }
}