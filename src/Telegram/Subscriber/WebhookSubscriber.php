<?php

declare(strict_types=1);

namespace App\Telegram\Subscriber;

use App\Webhook\Devscast\ContactSubmittedEvent;
use App\Webhook\Devscast\ContentCreatedEvent;
use App\Webhook\WebhookEventInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class WebhookEventSubscriber.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class WebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BotApi $api,
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Devscast
            ContactSubmittedEvent::class => 'onEvent',
        ];
    }

    public function onEvent(WebhookEventInterface $event): void
    {
        try {
            $this->send($event);
        } catch (\Throwable $e) {
            $this->logger->error($e, $e->getTrace());
        }
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function send(WebhookEventInterface $event): void
    {
        if (strlen((string) $event) !== 0) {
            $this->api->sendMessage(
                chatId: (string) $event->getChatId(),
                text: (string) $event,
                parseMode: 'MarkdownV2',
                disablePreview: true
            );
        }
    }
}
