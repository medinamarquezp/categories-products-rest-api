<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Exceptions\BaseException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\InvalidCurrencyException;
use App\Repository\ProductRepository;
use App\Service\ProductCurrencyService;
use App\Service\ValidateService;
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
  public function create(Request $request, ProductRepository $repo, ValidateService $v): JsonResponse
  {
    try {
      $product = $this->deserialize($request, Product::class);

      $errors = $v->validateCategory($request, $product);
      if ($errors) throw new ValidationException($errors);

      $repo->save($product);
      return $this->success("Product created correctly");
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }

  /**
   * @Route("/product", methods={"GET"})
   */
  public function getList(ProductRepository $repo): JsonResponse
  {
    try {
      $products = $repo->getList();
      if (!$products) throw new NotFoundException("No products found");

      return $this->success(null, $products);
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }

  /**
   * @Route("/product/featured", methods={"GET"})
   */
  public function getFeaturedList(Request $request, ProductRepository $repo, ProductCurrencyService $pcs): JsonResponse
  {
    try {
      $currency = $request->get("currency");
      $products = $repo->getFeaturedList();
      if (!$products) throw new NotFoundException("No featured products found");

      if ($currency) {
        $isValidCurrency =  $currency === "EUR" || $currency === "USD";
        if (!$isValidCurrency) throw new InvalidCurrencyException("Currency parameter must be EUR or USD");
        $products = $pcs->update($products, $currency);
      }

      return $this->success(null, $products);
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }
}
