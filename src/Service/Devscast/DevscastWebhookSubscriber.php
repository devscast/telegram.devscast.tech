<?php

declare(strict_types=1);

namespace App\Service\Devscast;

use App\Service\Devscast\Event\DevscastWebhookEvent;
use TelegramBot\Api\BotApi;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\Devscast\Event\DevscastContactFormSubmittedEvent;
use App\Service\Devscast\Event\DevscastContentCreatedEvent;

class DevscastWebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DevscastContactFormSubmittedEvent::class => 'onEvent',
            DevscastContentCreatedEvent::class => 'onEvent'
        ];
    }

    public function onEvent(DevscastWebhookEvent $event): void
    {
        try {
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], (string) $event);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
