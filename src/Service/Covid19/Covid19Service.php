<?php

declare(strict_types=1);

namespace App\Service\Covid19;

use App\Service\ServiceUnavailableException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Covid19Service
{
    /**
     * @var string
     */
    public const COUNTRY_PATH = 'congo (kinshasa)--21.7587---4.0383';

    /**
     * @var string
     */
    public const COUNTRY_ISO = 'COD';

    /**
     * @var string
     */
    public const BASE_URL = 'https://covid19.mathdro.id/api/confirmed';

    public function __construct(
        private HttpClientInterface $client,
        private Covid19MessageFormatter $formatter
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getConfirmedCase(): string
    {
        try {
            $data = ($this->client->request("GET", self::BASE_URL))->toArray();
            $congo = array_filter($data, function ($d) {
                $key = strtolower(sprintf('%s--%s--%s', $d['countryRegion'], $d['long'], $d['lat']));
                return $d['iso3'] === self::COUNTRY_ISO || $key === self::COUNTRY_PATH;
            });

            if ($congo !== []) {
                $data = $data[array_key_first($congo)];
                return $this->formatter->format($data);
            }

            throw new \Exception('Aucune donnée disponible');
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
