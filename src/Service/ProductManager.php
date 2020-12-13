<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Component\Validator\RequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductManager
{

  private $em;
  private $productRepository;
  private $validator;
  private $serializer;
  private $exchangeRateService;

  public function __construct(
    EntityManagerInterface $em,
    ProductRepository $productRepository,
    ValidatorInterface $validator,
    SerializerInterface $serializer,
    ExchangeRateService $exchangeRateService
  )
  {
    $this->em = $em;
    $this->productRepository = $productRepository;
    $this->validator = $validator;
    $this->serializer = $serializer;
    $this->exchangeRateService = $exchangeRateService;
  }

  public function serialize(Request $request)
  {
    return $this->serializer->deserialize($request->getContent(), Product::class, 'json');
  }


  public function validate(Request $request, Product $product)
  {
    $hasCategoryOnRequest = !empty($request->get("category"));
    $categoryIsNull = empty($product->getCategory());
    $errorCategory = $hasCategoryOnRequest && $categoryIsNull;

    $validation = RequestValidator::validate($product, $this->validator);
    $hasValidationErrors = !empty($validation["errors"]);

    if($hasValidationErrors) {
      if ($errorCategory) {
        return $this->addCategoryError($validation["errors"]);
      }
      return $validation["errors"];
    } else {
      if ($errorCategory) {
        return $this->addCategoryError($validation["errors"]);
      }
    }
    return null;
  }

  private function addCategoryError(Array $errors): Array
  {
    $errorCategoryMessage = "Category selected does not exists";
    $errors["category"] = [$errorCategoryMessage];
    return $errors;
  }

  public function getList(): ?Array
  {
    $productList = $this->productRepository->findAll();
    $response = $this->parseResponseList($productList);
    return $response;
  }

  public function getFeaturedList(): ?Array
  {
    $productListFeatured = $this->productRepository->findByFeatured();
    $response = $this->parseResponseList($productListFeatured);
    return $response;
  }

  private function parseResponseList(Array $productList): Array
  {
    $response = [];
    if (!empty($productList)) {
      foreach ($productList as $product) {
        $categoryName = (!empty($product->getCategory())) ? $product->getCategory()->getName() : "";
        array_push($response, [
          "id" => $product->getId(),
          "name" => $product->getName(),
          "price" => $product->getPrice(),
          "currency" => $product->getCurrency(),
          "categoryName" => $categoryName,
        ]);
      }
    }
    return $response;
  }

  public function updatePrices(Array $products, string $newCurrency): Array {
    $updatedProducts = [];
    foreach ($products as $product) {
      $baseCurrency = $product["currency"];
      $productPrice = $product["price"];
      if ($baseCurrency != $newCurrency) {
        $convertedRate = $this->exchangeRateService->getConvertedRate($baseCurrency, $newCurrency, $productPrice);
        $product["currency"] = $newCurrency;
        $product["price"] =  number_format($convertedRate, 2);
      }
      array_push($updatedProducts, $product);
    }
    return $updatedProducts;
  }

  public function save(Product $product): Product
  {
    $this->em->persist($product);
    $this->em->flush();
    return $product;
  }
}
