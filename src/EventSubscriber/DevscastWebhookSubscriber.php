<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Devscast\ContactFormSubmittedEvent;
use App\Service\Formatter\DevscastMessageFormatter;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

/**
 * Class GithubWebhookSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class DevscastWebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private DevscastMessageFormatter $formatter,
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactFormSubmittedEvent::class => 'onContactFormSubmitted',
        ];
    }

    public function onContactFormSubmitted(ContactFormSubmittedEvent $event): void
    {
        try {
            $text = $this->formatter->contactMessage([
                'name' => $event->getName(),
                'email' => $event->getEmail(),
                'subject' => $event->getSubject(),
                'message' => $event->getMessage()
            ]);

            $this->api->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $text);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
