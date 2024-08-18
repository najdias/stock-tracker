<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AlphavantageClient
{
    private static string $GLOBAL_QUOTE_FUNCTION = 'GLOBAL_QUOTE';

    private string $apiUrl;

    private string $apiKey;

    public function __construct(private readonly HttpClientInterface $client)
    {
        $this->apiUrl = $_ENV['ALPHAVANTAGE_API_URL'];
        $this->apiKey = $_ENV['ALPHAVANTAGE_API_KEY'];
    }

    public function getGlobalQuote(string $symbol): ?array
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'query' => [
                'function' => self::$GLOBAL_QUOTE_FUNCTION,
                'symbol' => $symbol,
                'apikey' => $this->apiKey
            ]
        ]);

        $data = $response->toArray();

        if (empty($data['Global Quote'])) {
            return null;
        }

        return $data['Global Quote'];
    }
}
