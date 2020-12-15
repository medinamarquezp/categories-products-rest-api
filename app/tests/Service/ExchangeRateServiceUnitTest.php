<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\ExchangeRateService;
use App\Service\Fetch\FetchInterface;

class ExchangeRateServiceUnitTest extends TestCase
{
    /** @test */
    public function it_should_return_a_valid_exchange_rate()
    {
        $exchangeRate = 1.2162;
        $exchangeRSMock = [
            "rates" => [
                "USD" => $exchangeRate
            ],
            "base" => "EUR",
            "date" => "2020-12-14"
        ];
        $stub = $this->createMock(FetchInterface::class);
        $stub->method('get')
            ->willReturn($exchangeRSMock);
        $exchangeRateService = new ExchangeRateService($stub);
        $originalPrice = 10;
        $sut = $exchangeRateService->getConvertedRate("EUR", "USD", $originalPrice);
        $priceConverted = $originalPrice * $exchangeRate;
        $this->assertEquals($priceConverted, $sut);
    }
}
