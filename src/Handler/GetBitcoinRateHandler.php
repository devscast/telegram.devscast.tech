<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\BitcoinRateCommand;
use App\Event\BitcoinRateEvent;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class BitcoinRateHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class BitcoinRateHandler
{
    public const BASE_URL = 'https://api.coindesk.com/v1/bpi/currentprice.json';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function __invoke(BitcoinRateCommand $command): void
    {
        $update = $this->getRate();
        $this->dispatcher->dispatch(new BitcoinRateEvent($update));
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getRate(): array
    {
        try {
            return $this->client->request('GET', self::BASE_URL)->toArray();
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
