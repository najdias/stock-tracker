<?php

namespace App\Tests\Unit\Client;

use App\Service\AlphavantageClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AlphavantageClientTest extends TestCase
{
    private AlphavantageClient $client;
    private HttpClientInterface $httpClient;
    private ResponseInterface $response;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->client = new AlphavantageClient($this->httpClient);
    }

    public function testGetGlobalQuoteReturnsQuoteSuccessfully(): void
    {
        $this->response->method('toArray')->willReturn([
            'Global Quote' => [
                '01. symbol' => 'AAPL',
                '02. open' => '150.00',
                '03. high' => '155.00',
                '04. low' => '149.00',
                '05. price' => '154.00',
                '06. volume' => '1000000',
                '07. latest trading day' => '2023-10-01',
                '08. previous close' => '153.00',
                '09. change' => '1.00',
                '10. change percent' => '0.65%'
            ]
        ]);

        $this->httpClient->method('request')->willReturn($this->response);

        $quote = $this->client->getGlobalQuote('AAPL');

        $this->assertIsArray($quote);
        $this->assertArrayHasKey('01. symbol', $quote);
        $this->assertEquals('AAPL', $quote['01. symbol']);
    }

    public function testGetGlobalQuoteReturnsNullForEmptyResponse(): void
    {
        $this->response->method('toArray')->willReturn([]);

        $this->httpClient->method('request')->willReturn($this->response);

        $quote = $this->client->getGlobalQuote('AAPL');

        $this->assertNull($quote);
    }

    public function testGetGlobalQuoteReturnsNullForMissingGlobalQuote(): void
    {
        $this->response->method('toArray')->willReturn(['Some Other Key' => 'Some Value']);

        $this->httpClient->method('request')->willReturn($this->response);

        $quote = $this->client->getGlobalQuote('AAPL');

        $this->assertNull($quote);
    }
}
