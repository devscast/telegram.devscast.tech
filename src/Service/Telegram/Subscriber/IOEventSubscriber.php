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
use App\Service\HackerNews\Event\Input\HackerNewsEvent;
use App\Service\Imap\Event\Input\ImapEvent;
use App\Service\InputEventInterface;
use App\Service\Lulz\Event\Input\LulzEvent;
use App\Service\OutputEventInterface;
use App\Service\Quiz\Event\Input\QuizEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

final class IOEventSubscriber implements EventSubscriberInterface
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
            Covid19Event::class => 'onEvent',
            BitcoinEvent::class => 'onEvent',
            ImapEvent::class => 'onEvent',
            IssueEvent::class => 'onEvent',
            QuizEvent::class => 'onEvent',
            HackerNewsEvent::class => 'onEvent',
            LulzEvent::class => 'onEvent',

            // Output events (webhook)
            ContentCreatedEvent::class => 'onEvent',
            ContactSubmittedEvent::class => 'onEvent',

            PingEvent::class => 'onEvent',
            PushEvent::class => 'onEvent',
            IssuesEvent::class => 'onEvent',
        ];
    }

    public function onEvent(InputEventInterface|OutputEventInterface $event): void
    {
        try {
            if ($event instanceof QuizEvent) {
                if ($event->isMultipleCorrectAnswers() === false) {
                    $message = $this->api->sendMessage(
                        chatId: (string) $event->getTarget(),
                        text: (string) $event
                    );
                    $this->api->sendPoll(
                        chatId: (string) $event->getTarget(),
                        question: 'Votre choix ?',
                        options: $event->getAnswers(),
                        isAnonymous: true,
                        type: 'quiz',
                        correctOptionId: $event->getCorrectAnswerId(),
                        replyToMessageId: $message->getMessageId(),
                    );
                }
            } elseif ($event instanceof LulzEvent) {
                $this->api->sendDocument(
                    chatId: (string) $event->getTarget(),
                    document: new \CURLFile($event->getImageUrl(), 'image/gif'),
                    caption: (string) $event
                );
            } else {
                $this->api->sendMessage(
                    chatId: (string) $event->getTarget(),
                    text: (string) $event
                );
            }
        } catch (\Exception $e) {
            $this->logger->error($e, $e->getTrace());
        }
    }
}
