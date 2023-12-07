<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyService
{
    public function __construct(
        private HttpClientInterface $client
    ) {
    }

    public function convertEurToDollar(float $euroPrice): float
    {
        return $this->convertEur($euroPrice, 'USD');
    }

    public function convertEurToYen(float $euroPrice): float
    {
        return $this->convertEur($euroPrice, 'JPY');
    }

    private function convertEur(float $euroPrice, string $currency): float
    {
        $response = $this->client->request(
            'GET',
            'https://v6.exchangerate-api.com/v6/'
                . $_ENV['CURRENCY_KEY']
                . '/pair/EUR/'
                . $currency
                . '/'
                . $euroPrice
        );

        $content = $response->toArray();

        return $content['conversion_result'];
    }
}