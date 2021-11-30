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
use TelegramBot\Api\BotApi;

/**
 * Class GithubWebhookSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <bernard@devscast.tech>
 */
class GithubWebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private GithubMessageFormatter $formatter,
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
            IssuesEvent::class => 'onIssues',
            PushEvent::class => 'onPush',
            PingEvent::class => 'onPing',
        ];
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function onPing(GithubWebhookEventInterface $event): void
    {
        try {
            $data = $event->getPlayLoad();
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], "👉🏾 Github ping : {$data['zen']}");
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function onPush(GithubWebhookEventInterface $event): void
    {
        try {
            $text = $this->formatter->push($event->getPlayLoad());
            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @param GithubWebhookEventInterface $event
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function onIssues(GithubWebhookEventInterface $event): void
    {
        try {
            $data = $event->getPlayLoad();
            switch ($data['action']) {
                case 'assigned':
                    $text = $this->formatter->assignedIssue($data);
                    $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
                    break;

                case 'opened':
                    $text = $this->formatter->openedIssue($data);
                    $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
                    break;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
