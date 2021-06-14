<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Formatter\BitcoinMessageFormatter;
use App\Service\Formatter\Covid19MessageFormatter;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class BitcoinService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BitcoinService
{
    public const BASE_URL = 'https://api.coindesk.com/v1/bpi/currentprice.json';

    public function __construct(
        private HttpClientInterface $client,
        private BitcoinMessageFormatter $formatter
    ) {
    }

    /**
     * @return ?string
     * @throws ServiceUnavailableException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getRate(): ?string
    {
        try {
            $data = ($this->client->request("GET", self::BASE_URL))->toArray();
            return $this->formatter->format($data);
        } catch (ClientExceptionInterface |
        DecodingExceptionInterface |
        TransportExceptionInterface |
        ServerExceptionInterface |
        RedirectionExceptionInterface $e
        ) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
