<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\ExchangeRateService;
use App\Service\ExchangeRate\ExchangeRateInterface;

class ExchangeRateServiceUnitTest extends TestCase
{
    /** @test */
    public function it_should_return_a_valid_exchange_rate()
    {
        $exchangeRate = 1.5;
        $stub = $this->createMock(ExchangeRateInterface::class);
        $stub->method('fetch')
                ->willReturn($exchangeRate);
        $exchangeRateService = new ExchangeRateService($stub);
        $originalPrice = 10;
        $sut = $exchangeRateService->getConvertedRate("EUR", "USD", $originalPrice);
        $priceConverted = $originalPrice * $exchangeRate;
        $this->assertEquals($priceConverted, $sut);
    }
}