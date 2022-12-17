<?php

declare(strict_types=1);

namespace App\Service\Covid19;

use App\Service\ServiceUnavailableException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class Covid19Service.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class Covid19Service
{
    public const COUNTRY_PATH = 'congo (kinshasa)--21.7587---4.0383';

    public const COUNTRY_ISO = 'COD';

    public const BASE_URL = 'https://covid19.mathdro.id/api/confirmed';

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getConfirmedCase(): array
    {
        try {
            $data = ($this->client->request('GET', self::BASE_URL))->toArray();
            $congo = array_filter($data, function ($d) {
                $key = strtolower(sprintf('%s--%s--%s', $d['countryRegion'], $d['long'], $d['lat']));
                return $d['iso3'] === self::COUNTRY_ISO || $key === self::COUNTRY_PATH;
            });

            if ($congo !== []) {
                return $data[array_key_first($congo)];
            }

            throw new \Exception('Aucune donnée disponible');
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
