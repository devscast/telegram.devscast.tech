<?php

declare(strict_types=1);

namespace App\Service;

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
    private HttpClientInterface $client;
    private Covid19MessageFormatter $formatter;

    /**
     * Covid19Service constructor.
     * @param HttpClientInterface $client
     * @param Covid19MessageFormatter $formatter
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(HttpClientInterface $client, Covid19MessageFormatter $formatter)
    {
        $this->client = $client;
        $this->formatter = $formatter;
    }

    /**
     * @return ?string
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getRate(): ?string
    {
        $data = ($this->client->request("GET", self::BASE_URL))->toArray();
        return $this->formatter->format($data);
    }
}
