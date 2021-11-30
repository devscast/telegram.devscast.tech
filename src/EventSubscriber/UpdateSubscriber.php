<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\BitcoinUpdateEvent;
use App\Event\Covid19UpdateEvent;
use App\Event\EMailUpdateEvent;
use App\Event\Github\GithubIssueUpdateEvent;
use App\Event\MessageUpdateEventInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

/**
 * Class UpdateSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <bernard@devscast.tech>
 */
class UpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return string[]
     * @author bernard-ng <bernard@devscast.tech>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Covid19UpdateEvent::class => 'onMessageUpdate',
            BitcoinUpdateEvent::class => 'onMessageUpdate',
            GithubIssueUpdateEvent::class => 'onMessageUpdate',
            EMailUpdateEvent::class => 'onMessageUpdate'
        ];
    }

    /**
     * @param MessageUpdateEventInterface $event
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function onMessageUpdate(MessageUpdateEventInterface $event): void
    {
        try {
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $event->getUpdate());
        } catch (\Exception $e) {
            $this->logger->error($e, $e->getTrace());
        }
    }
}
