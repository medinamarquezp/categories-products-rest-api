<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
  /** @test */
  public function it_should_return_error_400_on_creating_with_invalid_data()
  {
      $client = static::createClient();
      $client->request(
          'POST',
          '/api/category',
          [],
          [],
          ['CONTENT_TYPE' => 'application/json'],
          '{"name":""}'
      );
      $content = $client->getResponse();
      $contentJson = json_decode($content->getContent());
      $nameErrors = $contentJson->error->name;

      $this->assertEquals(400, $client->getResponse()->getStatusCode());
      $this->assertTrue(in_array("This value should not be blank.", $nameErrors));
      $this->assertTrue(in_array("Name property must be at least 2 characters long", $nameErrors));
  }
}