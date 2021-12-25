<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Bitcoin\Event\Input\BitcoinEvent;
use App\Service\Covid19\Event\Input\Covid19Event;
use App\Service\Devscast\Event\Output\ContactSubmittedEvent;
use App\Service\Devscast\Event\Output\ContentCreatedEvent;
use App\Service\Github\Event\Input\IssueEvent;
use App\Service\Github\Event\Output\IssuesEvent;
use App\Service\Github\Event\Output\PingEvent;
use App\Service\Github\Event\Output\PushEvent;
use App\Service\Imap\Event\Input\ImapEvent;
use App\Service\InputEventInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

final class IOEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Input events (cron)
            Covid19Event::class => 'onEvent',
            BitcoinEvent::class => 'onEvent',
            ImapEvent::class => 'onEvent',
            IssueEvent::class => 'onEvent',

            // Output events (webhook)
            ContentCreatedEvent::class => 'onEvent',
            ContactSubmittedEvent::class => 'onEvent',

            PingEvent::class => 'onEvent',
            PushEvent::class => 'onEvent',
            IssuesEvent::class => 'onEvent'
        ];
    }

    public function onEvent(InputEventInterface $event): void
    {
        try {
            $this->api->sendMessage(
                chatId: $_ENV['TELEGRAM_CHAT_ID'],
                text: (string) $event
            );
        } catch (\Exception $e) {
            $this->logger->error($e, $e->getTrace());
        }
    }
}
