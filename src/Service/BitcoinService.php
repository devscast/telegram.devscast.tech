<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BitcoinService
{
    public const BASE_URL = 'https://api.coindesk.com/v1/bpi/currentprice.json';
    private HttpClientInterface $client;

    /**
     * Covid19Service constructor.
     * @param HttpClientInterface $client
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
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
        return <<< MESSAGE
Salut l'équipe pour le projet coinze.tech 
voici le cours du Bitcoin maintenant : \n
💰 1 BTC = **{$data['bpi']['USD']['rate']} USD**
💰 1 BTC = **{$data['bpi']['EUR']['rate']} EUR**
💰 1 BTC = **{$data['bpi']['GBP']['rate']} GBP**

**{$data['time']['updated']}**
MESSAGE;
    }
}
