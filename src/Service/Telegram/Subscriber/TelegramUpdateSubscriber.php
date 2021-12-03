<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Bitcoin\Event\BitcoinUpdateEvent;
use App\Service\Covid19\Event\Covid19UpdateEvent;
use App\Service\Github\Event\GithubIssueUpdateEvent;
use App\Service\Imap\Event\ImapUpdateEvent;
use App\Service\UpdateEventInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

final class TelegramUpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Covid19UpdateEvent::class => 'onMessageUpdate',
            BitcoinUpdateEvent::class => 'onMessageUpdate',
            GithubIssueUpdateEvent::class => 'onMessageUpdate',
            ImapUpdateEvent::class => 'onMessageUpdate'
        ];
    }

    public function onMessageUpdate(UpdateEventInterface $event): void
    {
        try {
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $event->getUpdate());
        } catch (\Exception $e) {
            $this->logger->error($e, $e->getTrace());
        }
    }
}
