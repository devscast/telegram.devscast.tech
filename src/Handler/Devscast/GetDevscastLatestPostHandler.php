<?php

declare(strict_types=1);

namespace App\Handler\Devscast;

use App\Command\GetDevscastLatestPostCommand;
use App\FeedReader;
use App\Telegram\Exception\ServiceUnavailableException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

/**
 * class GetDevscastLatestPostHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class GetDevscastLatestPostHandler
{
    use FeedReader;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function __invoke(GetDevscastLatestPostCommand $command): void
    {
        try {
            $data = $this->getData('posts');
            $message = sprintf('Nos derniers posts (mise à jour le %s)', $data['last_update']);

            $this->api->sendMessage(
                chatId: (string) $command->getChatId(),
                text: $message,
                replyToMessageId: $command->getReplyToMessageId(),
                replyMarkup: new InlineKeyboardMarkup($data['items']),
                messageThreadId: $command->getMessage()?->getMessageThreadId()
            );
        } catch (\Throwable $e) {
            dump($e);
            $this->logger->error($e->getMessage(), $e->getTrace());
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
