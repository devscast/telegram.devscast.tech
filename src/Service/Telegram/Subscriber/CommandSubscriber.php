<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Telegram\Event\CommandEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

final class CommandSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BotApi $api,
        private readonly LoggerInterface $logger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommandEvent::class => 'onCommand',
        ];
    }

    public function onCommand(CommandEvent $event): void
    {
        $messageId = $event->getMessage()->getMessageId();
        $chatId = $event->getMessage()->getChat()->getId();

        try {
            switch ($event->getCommand()) {
                default:
                case '/start@DevscastNotifierBot':
                case '/start':
                    $this->api->sendMessage($chatId, "Hi I'm DevscastNotifierBot", replyToMessageId: $messageId);
                    break;
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
