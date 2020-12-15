<?php

namespace App\Service;

class ProductCurrencyService {

  private $ers;

  public function __construct(ExchangeRateService $ers)
  {
      $this->ers = $ers;
  }

  public function update(Array $products, string $newCurrency): Array {
    $updatedProducts = [];
    foreach ($products as $product) {
      $baseCurrency = $product["currency"];
      $productPrice = $product["price"];
      if ($baseCurrency != $newCurrency) {
        $convertedRate = $this->ers->getConvertedRate($baseCurrency, $newCurrency, $productPrice);
        $product["currency"] = $newCurrency;
        $product["price"] =  number_format($convertedRate, 2);
      }
      array_push($updatedProducts, $product);
    }
    return $updatedProducts;
  }

}