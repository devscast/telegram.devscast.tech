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
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

/**
 * Class UpdateSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class UpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ChatterInterface $notifier,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
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
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onMessageUpdate(MessageUpdateEventInterface $event): void
    {
        try {
            $message = new ChatMessage($event->getUpdate());
            $this->notifier->send($message);
        } catch (\Exception | TransportExceptionInterface $e) {
            $this->logger->error($e);
        }
    }
}
