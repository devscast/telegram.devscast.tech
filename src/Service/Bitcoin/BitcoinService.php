<?php

declare(strict_types=1);

namespace App\Service\Bitcoin;

use App\Service\ServiceUnavailableException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BitcoinService
{
    /**
     * @var string
     */
    public const BASE_URL = 'https://api.coindesk.com/v1/bpi/currentprice.json';

    public function __construct(
        private HttpClientInterface $client,
        private BitcoinMessageFormatter $formatter
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getRate(): string
    {
        try {
            $data = ($this->client->request("GET", self::BASE_URL))->toArray();
            return $this->formatter->format($data);
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
