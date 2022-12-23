<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\CovidUpdateCommand;
use App\Event\BitcoinRateEvent;
use App\Event\CovidUpdateEvent;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class CovidUpdateHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class CovidUpdateHandler
{
    public const COUNTRY_PATH = 'congo (kinshasa)--21.7587---4.0383';

    public const COUNTRY_ISO = 'COD';

    public const BASE_URL = 'https://covid19.mathdro.id/api/confirmed';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function __invoke(CovidUpdateCommand $command): void
    {
        $update = $this->getConfirmedCase();
        $this->dispatcher->dispatch(new CovidUpdateEvent($update));
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
