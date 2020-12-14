<?php

namespace App\Service\ExchangeRate;

interface ExchangeRateInterface {
  function fetch(string $baseCurrency, string $newCurrency): float;
}