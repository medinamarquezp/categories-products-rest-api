<?php

namespace App\Service;

use App\Service\Fetch\FetchInterface;

class ExchangeRateService {

  private $fetch;

  public function __construct(FetchInterface $fetch)
  {
      $this->fetch = $fetch;
  }

  public function getConvertedRate(string $baseCurrency, string $newCurrency, float $rate): float
  {
    $path = "https://api.exchangeratesapi.io/latest?base=" . $baseCurrency . "&symbols=" . $newCurrency;
    $exchangeResponse = $this->fetch->get($path);
    $exchangeRate = $exchangeResponse["rates"][$newCurrency] ?: 1;
    $convertedRate = $rate * $exchangeRate;
    return $convertedRate;
  }
}