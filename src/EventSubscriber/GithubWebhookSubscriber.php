<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Github\Webhook\GithubWebhookEventInterface;
use App\Event\Github\Webhook\IssuesEvent;
use App\Event\Github\Webhook\PingEvent;
use App\Event\Github\Webhook\PushEvent;
use App\Service\Formatter\GithubMessageFormatter;
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
    public function __construct(
        private ChatterInterface $notifier,
        private GithubMessageFormatter $formatter,
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
            IssuesEvent::class => 'onIssues',
            PushEvent::class => 'onPush',
            PingEvent::class => 'onPing',
        ];
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPing(GithubWebhookEventInterface $event): void
    {
        try {
            $data = $event->getPlayLoad();
            $this->notifier->send(new ChatMessage("👉🏾 Github ping : {$data['zen']}"));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onPush(GithubWebhookEventInterface $event): void
    {
        try {
            $data = $event->getPlayLoad();
            $message = $this->formatter->push($data);
            $this->notifier->send(new ChatMessage($message));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onIssues(GithubWebhookEventInterface $event): void
    {
        try {
            $data = $event->getPlayLoad();
            switch ($data['action']) {
                case 'assigned':
                    $message = $this->formatter->assignedIssue($data);
                    $this->notifier->send(new ChatMessage($message));
                    break;
            }
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
