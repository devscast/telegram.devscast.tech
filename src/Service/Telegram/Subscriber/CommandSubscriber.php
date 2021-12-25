<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Bitcoin\BitcoinService;
use App\Service\Bitcoin\Event\Input\BitcoinEvent;
use App\Service\Covid19\Covid19Service;
use App\Service\Covid19\Event\Input\Covid19Event;
use App\Service\ServiceUnavailableException;
use App\Service\Telegram\Event\CommandEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;

final class CommandSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi                 $api,
        private Covid19Service         $covid19Service,
        private BitcoinService         $bitcoinService,
        private LoggerInterface        $logger,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            CommandEvent::class => 'onCommand'
        ];
    }

    public function onCommand(CommandEvent $event): void
    {
        $messageId = $event->getMessage()->getMessageId();
        $chatId = $event->getMessage()->getChat()->getId();

        // TODO: route command
        try {
            try {
                switch ($event->getCommand()) {
                    case '/start@DevscastNotifierBot':
                    case '/start':
                        $this->api->sendMessage($chatId, "Hi I'm DevscastBot", replyToMessageId: $messageId);
                        break;

                    case '/covid19@DevscastNotifierBot':
                    case '/covid19':
                        $event = new Covid19Event($this->covid19Service->getConfirmedCase());
                        $this->api->sendMessage($chatId, (string) $event, replyToMessageId: $messageId);
                        break;

                    case '/bitcoin@DevscastNotifierBot':
                    case '/bitcoin':
                        $event = new BitcoinEvent($this->bitcoinService->getRate());
                        $this->api->sendMessage($chatId, (string) $event, replyToMessageId: $messageId);
                        break;
                }
            } catch (ServiceUnavailableException) {
                $this->api->sendMessage(
                    chatId: $chatId,
                    text: "Service Indisponible pour l'instant !",
                    replyToMessageId: $messageId
                );
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
