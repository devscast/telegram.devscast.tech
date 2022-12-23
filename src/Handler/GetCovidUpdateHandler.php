<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\GetCovidUpdateCommand;
use App\Telegram\Exception\ServiceUnavailableException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class CovidUpdateHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class GetCovidUpdateHandler
{
    public const COUNTRY_PATH = 'congo (kinshasa)--21.7587---4.0383';

    public const COUNTRY_ISO = 'COD';

    public const BASE_URL = 'https://covid19.mathdro.id/api/confirmed';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(GetCovidUpdateCommand $command): void
    {
        $update = $this->getUpdate();
        $text = "😷 Covid19RDC,
        \n Cas Confirmés[*{$update['confirmed']}*] - Décès[*{$update['deaths']}*] - Guérisons[*{$update['recovered']}*]";

        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: $text,
            parseMode: 'MarkdownV2',
            replyToMessageId: $command->getReplyToMessageId()
        );
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getUpdate(): array
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
