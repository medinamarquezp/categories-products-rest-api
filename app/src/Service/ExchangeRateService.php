<?php

namespace App\Service;

use App\Service\ExchangeRate\ExchangeRateInterface;

class ExchangeRateService {

  private $er;

  public function __construct(ExchangeRateInterface $er)
  {
      $this->er = $er;
  }

  public function getConvertedRate(string $baseCurrency, string $newCurrency, float $rate): float
  {
    $exchangeRate = $this->er->fetch($baseCurrency, $newCurrency);
    $convertedRate = $rate * $exchangeRate;
    return $convertedRate;
  }


}