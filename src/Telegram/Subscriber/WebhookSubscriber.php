<?php

declare(strict_types=1);

namespace App\Telegram;

use App\CommandEventInterface;
use App\Event\BitcoinRateEvent;
use App\Event\CovidUpdateEvent;
use App\Event\Devscast\ContactSubmittedEvent;
use App\Event\Devscast\ContentCreatedEvent;
use App\Event\Github\ForkEvent;
use App\Event\Github\IssuesEvent;
use App\Event\Github\OpenIssuesEvent;
use App\Event\Github\PingEvent;
use App\Event\Github\PullRequestEvent;
use App\Event\Github\PullRequestReviewEvent;
use App\Event\Github\PushEvent;
use App\Event\Github\StarEvent;
use App\Event\Github\StatusEvent;
use App\Event\HackerNewsStoriesEvent;
use App\Event\LulzEvent;
use App\Event\QuizEvent;
use App\Event\UnreadEmailEvent;
use App\WebhookEventInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\HttpException;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\InvalidJsonException;

/**
 * class WebhookEventSubscriber.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class WebhookEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BotApi $api,
        private readonly LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Input events (cron)
            CovidUpdateEvent::class => 'onEvent',
            BitcoinRateEvent::class => 'onEvent',
            UnreadEmailEvent::class => 'onEvent',
            OpenIssuesEvent::class => 'onEvent',
            HackerNewsStoriesEvent::class => 'onEvent',
            QuizEvent::class => 'onEvent',
            LulzEvent::class => 'onEvent',

            // Output events (webhook)
            ContentCreatedEvent::class => 'onEvent',
            ContactSubmittedEvent::class => 'onEvent',

            PingEvent::class => 'onEvent',
            PushEvent::class => 'onEvent',
            IssuesEvent::class => 'onEvent',
            ForkEvent::class => 'onEvent',
            PullRequestEvent::class => 'onEvent',
            PullRequestReviewEvent::class => 'onEvent',
            StarEvent::class => 'onEvent',
            StatusEvent::class => 'onEvent',
        ];
    }

    public function onEvent(WebhookEventInterface|CommandEventInterface $event): void
    {
        try {
            match (true) {
                $event instanceof QuizEvent => $this->sendQuiz($event),
                default => $this->send($event)
            };
        } catch (\Throwable $e) {
            $this->logger->error($e, $e->getTrace());
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws InvalidJsonException
     * @throws Exception
     * @throws HttpException
     */
    private function sendQuiz(QuizEvent $event): void
    {
        if ($event->isMultipleCorrectAnswers() === false) {
            $message = $this->api->sendMessage(
                chatId: (string) $event->getChatId(),
                text: (string) $event
            );
            $this->api->sendPoll(
                chatId: (string) $event->getChatId(),
                question: 'Votre choix ?',
                options: $event->getAnswers(),
                isAnonymous: true,
                type: 'quiz',
                correctOptionId: $event->getCorrectAnswerId(),
                replyToMessageId: $message->getMessageId(),
            );
        }
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function send(CommandEventInterface|WebhookEventInterface $event): void
    {
        if (strlen((string) $event) !== 0) {
            $this->api->sendMessage(
                chatId: (string) $event->getChatId(),
                text: (string) $event
            );
        }
    }
}
