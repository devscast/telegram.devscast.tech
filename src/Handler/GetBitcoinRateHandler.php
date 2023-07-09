<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\GetBitcoinRateCommand;
use App\Telegram\Exception\ServiceUnavailableException;
use App\Telegram\Topic;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use Throwable;

/**
 * class BitcoinRateHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class GetBitcoinRateHandler
{
    public const BASE_URL = 'https://api.coindesk.com/v1/bpi/currentprice.json';

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
    public function __invoke(GetBitcoinRateCommand $command): void
    {
        $update = $this->getUpdate();
        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: "🤑 1 BTC == {$update['bpi']['USD']['rate']} USD",
            replyToMessageId: $command->getReplyToMessageId(),
            disableNotification: true,
            messageThreadId: Topic::resources()->toInt()
        );
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getUpdate(): array
    {
        try {
            return $this->client->request('GET', self::BASE_URL)->toArray();
        } catch (Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
