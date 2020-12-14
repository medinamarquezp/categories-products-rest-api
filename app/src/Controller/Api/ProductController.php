<?php

namespace App\Controller\Api;

use App\Service\ProductManager;
use App\Service\ExchangeRateService;
use App\Exceptions\BaseException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\InvalidCurrencyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class ProductController extends BaseController
{

  /**
   * @Route("/product", methods={"POST"})
   */
  public function create(Request $request, ProductManager $productManager): JsonResponse
  {
    try {
      $product = $productManager->serialize($request);

      $errors = $productManager->validate($request, $product);
      if ($errors) throw new ValidationException($errors);

      $productManager->save($product);
      return $this->success("Product created correctly");
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }

  /**
   * @Route("/product", methods={"GET"})
   */
  public function getList(ProductManager $productManager): JsonResponse
  {
    try {
      $products = $productManager->getList();
      if (!$products) throw new NotFoundException("No products found");

      return $this->success(null, $products);
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }

  /**
   * @Route("/product/featured", methods={"GET"})
   */
  public function getFeaturedList(Request $request, ExchangeRateService $exchangeRateService, ProductManager $productManager): JsonResponse
  {
    try {
      $currency = $request->get("currency");
      $products = $productManager->getFeaturedList();
      if (!$products) throw new NotFoundException("No featured products found");

      if ($currency) {
        $isValidCurrency =  $currency === "EUR" || $currency === "USD";
        if (!$isValidCurrency) throw new InvalidCurrencyException("Currency parameter must be EUR or USD");
        $products = $productManager->updatePrices($products, $currency);
      }

      return $this->success(null, $products);
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }
}
