<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Github\Webhook\GithubWebhookEventInterface;
use App\Event\Github\Webhook\IssuesEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

/**
 * Class GithubWebhookSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubWebhookSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ChatterInterface $notifier;

    /**
     * UpdateSubscriber constructor.
     * @param ChatterInterface $notifier
     * @param LoggerInterface $logger
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(ChatterInterface $notifier, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->notifier = $notifier;
    }

    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            IssuesEvent::class => 'onIssues'
        ];
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onIssues(GithubWebhookEventInterface $event): void
    {
        try {
            $this->notifier->send(new ChatMessage($event->getName() . " " . $event->getGuid()));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
