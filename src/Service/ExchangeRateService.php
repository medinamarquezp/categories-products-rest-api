<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService {

  private $client;

  public function __construct(HttpClientInterface $client)
  {
      $this->client = $client;
  }

  public function getConvertedRate(string $baseCurrency, string $newCurrency, float $rate): float
  {
    $exchangeRate = $this->fetchExchangeRate($baseCurrency, $newCurrency);
    $convertedRate = $rate * $exchangeRate;
    return $convertedRate;
  }


  public function fetchExchangeRate(string $baseCurrency, string $newCurrency): float
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