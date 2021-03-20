<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Github\Webhook\GithubWebhookEventInterface;
use App\Event\Github\Webhook\IssuesEvent;
use App\Event\Github\Webhook\PingEvent;
use App\Event\Github\Webhook\PushEvent;
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
        $data = $event->getPlayLoad();
        try {
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
        $data = $event->getPlayLoad();
        $commit = substr(strval($data['after']), 0, 8);
        $project = $data['repository']['name'];
        $pusher = $data['pusher']['name'];
        $message = $data['head_commit']['message'];

        $message = <<< MESSAGE
⬆️ Push sur le project {$project}
🗒 {$message} <{$commit}>
👨‍💻 {$pusher}
MESSAGE;

        try {
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
        $data = $event->getPlayLoad();
        try {
            switch ($data['action']) {
                case 'assigned':
                    $title = "#{$data['issue']['number']} {$data['issue']['title']}";
                    $assignee = $data['assignee']['login'];
                    $project = $data['repository']['name'];
                    $message = <<< MESSAGE
👨🏽‍🔧 Assignation de tache pour **{$assignee}**
"{$title}" sur le projet {$project}
MESSAGE;
                    $this->notifier->send(new ChatMessage($message));
                    break;
            }


            $this->notifier->send(new ChatMessage($event->getName() . " " . $event->getGuid()));
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
