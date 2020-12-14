<?php

namespace App\Controller\Api;

use App\Component\Http\ApiResponse;
use App\Service\ExchangeRateService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ProductManager;


/**
 * @Route("/api")
 */
class ProductController {

  /**
   * @Route("/product", methods={"POST"})
   */
  public function create(Request $request, ProductManager $productManager): JsonResponse
  {
      $product = $productManager->serialize($request);

      $errors = $productManager->validate($request, $product);
      if ($errors) {
        $response = new ApiResponse(Response::HTTP_BAD_REQUEST);
        return $response->error($errors);
      }

      $productManager->save($product);

      $response = new ApiResponse(Response::HTTP_OK);
      return $response->success("Product created correctly");
  }

  /**
   * @Route("/product", methods={"GET"})
   */
  public function getList(ProductManager $productManager): JsonResponse
  {
      $products = $productManager->getList();

      if (empty($products)) {
        $response = new ApiResponse(Response::HTTP_NOT_FOUND);
        return $response->error("No products found");
      }

      $response = new ApiResponse(Response::HTTP_OK);
      return $response->success($products);
  }

  /**
   * @Route("/product/featured", methods={"GET"})
   */
  public function getFeaturedList(Request $request, ExchangeRateService $exchangeRateService, ProductManager $productManager): JsonResponse
  {
      $currency = $request->get("currency");
      $products = $productManager->getFeaturedList();

      if (empty($products)) {
        $response = new ApiResponse(Response::HTTP_NOT_FOUND);
        return $response->error("No featured products found");
      }

      if($currency) {
        $isValidCurrency =  $currency === "EUR" || $currency === "USD";

        if(!$isValidCurrency) {
          $response = new ApiResponse(Response::HTTP_BAD_REQUEST);
          return $response->error("Currency parameter must be EUR or USD");
        }
        $products = $productManager->updatePrices($products, $currency);
      }

      $response = new ApiResponse(Response::HTTP_OK);
      return $response->success($products);
  }

}