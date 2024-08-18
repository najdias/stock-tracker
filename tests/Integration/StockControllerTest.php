<?php

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\StockLogRepository;
use App\Service\AlphavantageClient;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class StockControllerTest extends WebTestCase
{
    private User $user;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();

        $entityManager = $container->get('doctrine')->getManager();

        $this->user = (new User())
            ->setEmail('aaaa@aa.aa')
            ->setPassword('aaaa');

        $entityManager->persist($this->user);
        $entityManager->flush();

        $alphaVantageClient = $this->createMock(AlphavantageClient::class);
        $alphaVantageClient
            ->method('getGlobalQuote')
            ->willReturnMap([
                ['IBM', [
                    '02. open' => 100.0,
                    '03. high' => 110.0,
                    '04. low' => 90.0,
                    '08. previous close' => 105.0,
                ]],
                ['INVALID', null],
            ]);
        $container->set(AlphavantageClient::class, $alphaVantageClient);

        $stockLogRepository = $this->createMock(StockLogRepository::class);
        $stockLogRepository
            ->method('findAllByUserOrderedByDate')
            ->with($this->user)
            ->willReturn([
                [
                    'date' => '2021-01-01T00:00:00Z',
                    'symbol' => 'AAPL',
                    'open' => 100.0,
                    'high' => 110.0,
                    'low' => 90.0,
                    'close' => 105.0,
                ],
            ]);
        $container->set(StockLogRepository::class, $stockLogRepository);
    }

    public function testGetStockReturnsQuoteSuccessfully(): void
    {
        $this->client->loginUser($this->user);

        $this->client->request('GET', '/api/stock?q=IBM');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('IBM', $responseContent['symbol']);
        $this->assertEquals(100.0, $responseContent['open']);
        $this->assertEquals(110.0, $responseContent['high']);
        $this->assertEquals(90.0, $responseContent['low']);
        $this->assertEquals(105.0, $responseContent['close']);
    }

    public function testGetStockReturnsBadRequestForInvalidSymbol(): void
    {
        $this->client->loginUser($this->user);

        $this->client->request('GET', '/api/stock?q=INVALID');

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseContent);
        $this->assertEquals('Invalid symbol or Alphavantage API limit reached', $responseContent['message']);
    }

    public function testGetHistoryReturnsUserHistorySuccessfully(): void
    {
        $this->client->loginUser($this->user);

        $this->client->request('GET', '/api/stock/history');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(1, $responseContent);
        $this->assertEquals('AAPL', $responseContent[0]['symbol']);
        $this->assertEquals(100.0, $responseContent[0]['open']);
        $this->assertEquals(110.0, $responseContent[0]['high']);
        $this->assertEquals(90.0, $responseContent[0]['low']);
        $this->assertEquals(105.0, $responseContent[0]['close']);
    }
}
