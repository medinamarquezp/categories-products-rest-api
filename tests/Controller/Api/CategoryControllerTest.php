<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CategoryControllerTest extends WebTestCase
{
  /** @test */
  public function it_should_validate_null_required_parameter()
  {
    $client = static::createClient();
    $contentJson = $this->createCategoryRequest($client, '{}');
    $nameErrors = $contentJson->error->name;
    $errorMessage = "Name property should not be null";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $nameErrors));
  }

  /** @test */
  public function it_should_validate_empty_required_parameter()
  {
    $client = static::createClient();
    $contentJson = $this->createCategoryRequest($client, '{"name":""}');
    $nameErrors = $contentJson->error->name;
    $errorMessage = "Name property should not be blank";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $nameErrors));
  }

  /** @test */
  public function it_should_validate_parameter_length()
  {
    $client = static::createClient();
    $contentJson = $this->createCategoryRequest($client, '{"name":"a"}');
    $nameErrors = $contentJson->error->name;
    $errorMessage = "Name property must be at least 2 characters long";

    $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    $this->assertTrue(in_array($errorMessage, $nameErrors));
  }

  /** @test */
  public function it_should_create_a_new_category()
  {
    $client = static::createClient();
    $timestamp = time();
    $categoryName = "Test category " . $timestamp;
    $categoryDescription = "Test categoy description";
    $contentJson = $this->createCategoryRequest($client, '{"name": "'.$categoryName.'", "description": "'.$categoryDescription.'"}');
    $cratedCategoryName = $contentJson->data->name;
    $cratedCategoryDescription = $contentJson->data->description;
    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertEquals($cratedCategoryName, $categoryName);
    $this->assertEquals($cratedCategoryDescription, $categoryDescription);
  }

  /** @test */
  public function it_should_update_a_category()
  {
    $client = static::createClient();
    $timestamp = time();
    $categoryName = "Test category " . $timestamp;
    $categoryCreated = $this->createCategoryRequest($client, '{"name":"'.$categoryName.'"}');
    $createdCategoryID = $categoryCreated->data->id;
    $newCategoryName = "Test category " . $timestamp . " UPDATED";
    $categoryUpdated = $this->updateCategoryRequest($client, $createdCategoryID, '{"name": "'.$newCategoryName.'"}');
    $categoryUpdatedName = $categoryUpdated->data->name;
    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertEquals($categoryUpdatedName, $newCategoryName);
  }

  /** @test */
  public function it_should_delete_a_category()
  {
    $client = static::createClient();
    $timestamp = time();
    $categoryName = "Test category " . $timestamp;
    $categoryCreated = $this->createCategoryRequest($client, '{"name":"'.$categoryName.'"}');
    $createdCategoryID = $categoryCreated->data->id;
    $deletedResponse = $this->deleteCategoryRequest($client, $createdCategoryID);
    $deletedData = $deletedResponse->data;
    $deletedMessage = "Category deleted correctly";
    $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    $this->assertEquals($deletedData, $deletedMessage);
  }

  private function createCategoryRequest($client, $body)
  {
    $client->request(
      'POST',
      '/api/category',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      $body
    );
    $content = $client->getResponse();
    $contentJson = json_decode($content->getContent());
    return $contentJson;
  }

  private function updateCategoryRequest($client, $id, $body)
  {
    $client->request(
      'PUT',
      '/api/category/'.$id,
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      $body
    );
    $content = $client->getResponse();
    $contentJson = json_decode($content->getContent());
    return $contentJson;
  }

  private function deleteCategoryRequest($client, $id)
  {
    $client->request(
      'DELETE',
      '/api/category/'.$id,
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
