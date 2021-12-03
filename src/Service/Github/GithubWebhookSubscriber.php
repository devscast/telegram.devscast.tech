<?php

declare(strict_types=1);

namespace App\Service\Github;

use App\Service\Github\Event\Webhook\GithubIssuesEvent;
use App\Service\Github\Event\Webhook\GithubPingEvent;
use App\Service\Github\Event\Webhook\GithubPushEvent;
use App\Service\Github\Event\Webhook\GithubWebhookEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

final class GithubWebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private GithubMessageFormatter $formatter,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            GithubIssuesEvent::class => 'onIssues',
            GithubPushEvent::class => 'onPush',
            GithubPingEvent::class => 'onPing',
        ];
    }

    public function onPing(GithubWebhookEvent $event): void
    {
        try {
            /** @var array $data */
            $data = $event->getData();
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], sprintf('👉🏾 Github ping : %s', $data['zen']));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    public function onPush(GithubWebhookEvent $event): void
    {
        try {
            $text = $this->formatter->push($event->getData());
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    public function onIssues(GithubWebhookEvent $event): void
    {
        try {
            /** @var array $data */
            $data = $event->getData();
            if ($data['action'] == 'assigned') {
                $text = $this->formatter->assignedIssue($data);
                $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
            } elseif ($data['action'] == 'opened') {
                $text = $this->formatter->openedIssue($data);
                $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
