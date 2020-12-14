<?php

namespace App\Tests\Component\Http;

use App\Component\Http\ApiResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseUnitTest extends TestCase
{
    /** @test */
    public function response_should_by_json()
    {
        $statusCode = Response::HTTP_OK;
        $message = "OK";
        $apiResponse = new ApiResponse($statusCode);
        $success = $apiResponse->success($message);
        $sut = $success->getContent();
        $this->assertJson($sut);
    }

    /** @test */
    public function ok_response_should_return_correct_content()
    {
        $statusCode = Response::HTTP_OK;
        $data = "OK";
        $apiResponse = new ApiResponse($statusCode);
        $success = $apiResponse->success($data);
        $content = json_decode($success->getContent());
        $responseStatusCode = $content->statusCode;
        $responseData = $content->data;
        $this->assertEquals($responseStatusCode, $statusCode);
        $this->assertEquals($responseData, $data);
    }

    /** @test */
    public function error_response_should_return_correct_content()
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $errorMessage = "Server Error";
        $apiResponse = new ApiResponse($statusCode);
        $error = $apiResponse->error($errorMessage);
        $content = json_decode($error->getContent());
        $responseStatusCode = $content->statusCode;
        $responseError = $content->error;
        $this->assertEquals($responseStatusCode, $statusCode);
        $this->assertEquals($responseError, $errorMessage);
    }
}