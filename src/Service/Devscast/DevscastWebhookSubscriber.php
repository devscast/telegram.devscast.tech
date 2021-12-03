<?php

declare(strict_types=1);

namespace App\Service\Devscast;

use App\Service\Devscast\Event\DevscastContactFormSubmittedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

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
            DevscastContactFormSubmittedEvent::class => 'onContactFormSubmitted',
        ];
    }

    public function onContactFormSubmitted(DevscastContactFormSubmittedEvent $event): void
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
