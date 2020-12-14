<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
  /** @test */
  public function it_should_validate_null_required_parameter()
  {
    $client = static::createClient();
    $contentJson = $this->createProductRequest($client, '{}');
    $nameErrors = $contentJson->error->name;
    $errorMessage = "Name property should not be null";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $nameErrors));
  }

  /** @test */
  public function it_should_validate_empty_required_parameter()
  {
    $client = static::createClient();
    $contentJson = $this->createProductRequest($client, '{"name":""}');
    $nameErrors = $contentJson->error->name;
    $errorMessage = "Name property should not be blank";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $nameErrors));
  }

  /** @test */
  public function it_should_validate_not_found_category()
  {
    $client = static::createClient();
    $contentJson = $this->createProductRequest($client, '{"category":9999999999}');
    $categoryErrors = $contentJson->error->category;
    $errorMessage = "Category selected does not exists";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $categoryErrors));
  }

  /** @test */
  public function it_should_validate_invalid_currency()
  {
    $client = static::createClient();
    $contentJson = $this->createProductRequest($client, '{"currency":"GBP"}');
    $currencyErrors = $contentJson->error->currency;
    $errorMessage = "Currency property must be EUR or USD";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $currencyErrors));
  }

  /** @test */
  public function it_should_create_a_new_product_withoug_category()
  {
    $client = static::createClient();
    $requestBody = '{
      "name": "New product withoug category '.time().'",
      "price": 20.50,
      "currency": "EUR",
      "featured": false
    }';
    $contentJson = $this->createProductRequest($client, $requestBody);
    $createdResponseMessage = $contentJson->message;
    $createdMessage = "Product created correctly";
    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertStringContainsString($createdResponseMessage, $createdMessage);
  }

  /** @test */
  public function it_should_create_a_new_product_with_category()
  {
    $client = static::createClient();
    $requestBody = '{
      "name": "New product with category '.time().'",
      "category": 10,
      "price": 20.50,
      "currency": "EUR",
      "featured": true
    }';
    $contentJson = $this->createProductRequest($client, $requestBody);
    $createdResponseMessage = $contentJson->message;
    $createdMessage = "Product created correctly";
    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertStringContainsString($createdResponseMessage, $createdMessage);
  }

  /** @test */
  public function it_should_get_all_products()
  {
    $client = static::createClient();
    $response = $this->getProductRequest($client, "/api/product");
    $products = $response->data;
    $totalProducts = count($products);
    $hasMoreThan1 = $totalProducts > 1;

    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertTrue($hasMoreThan1);
  }

  /** @test */
  public function it_should_validate_currency_converter()
  {
    $client = static::createClient();

    $featuredResponse = $this->getProductRequest($client, "/api/product/featured");
    $featuredProductList = $featuredResponse->data;
    $lastFeaturedProduct = $featuredProductList[count($featuredProductList) - 1];

    $updatedCurrencyResponse = $this->getProductRequest($client, "/api/product/featured?currency=USD");
    $listUpdatedCurrency = $updatedCurrencyResponse->data;
    $lastUpdatedCurrencyProduct = $listUpdatedCurrency[count($listUpdatedCurrency) - 1];

    $hasDifferentCurrencies = $lastFeaturedProduct->currency != $lastUpdatedCurrencyProduct->currency;
    $isUSDPriceGreather = $lastUpdatedCurrencyProduct->price > $lastFeaturedProduct->price;

    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertTrue($hasDifferentCurrencies);
    $this->assertTrue($isUSDPriceGreather);
  }

  private function createProductRequest($client, $body)
  {
    $client->request(
      'POST',
      '/api/product',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      $body
    );
    $content = $client->getResponse();
    $contentJson = json_decode($content->getContent());
    return $contentJson;
  }

  private function getProductRequest($client, $url)
  {
    $client->request(
      'GET',
      $url,
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      ''
    );
    $content = $client->getResponse();
    $contentJson = json_decode($content->getContent());
    return $contentJson;
  }
}
