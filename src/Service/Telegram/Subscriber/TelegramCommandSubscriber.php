<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Bitcoin\BitcoinService;
use App\Service\Covid19\Covid19Service;
use App\Service\Telegram\Event\TelegramCommandFiredEvent;
use App\Service\ServiceUnavailableException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\InvalidArgumentException;

final class TelegramCommandSubscriber implements EventSubscriberInterface
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
            TelegramCommandFiredEvent::class => 'onCommandFired'
        ];
    }

    public function onCommandFired(TelegramCommandFiredEvent $event): void
    {
        $messageId = $event->getMessage()->getMessageId();
        $chatId = $event->getMessage()->getChat()->getId();

        try {
            try {
                switch ($event->getCommand()) {
                    case '/start@DevscastNotifierBot':
                    case '/start':
                        $this->api->sendMessage($chatId, "Hi I'm DevscastBot", replyToMessageId: $messageId);
                        break;

                    case '/covid19@DevscastNotifierBot':
                    case '/covid19':
                        $text = $this->covid19Service->getConfirmedCase();
                        $this->api->sendMessage($chatId, $text, replyToMessageId: $messageId);
                        break;

                    case '/bitcoin@DevscastNotifierBot':
                    case '/bitcoin':
                        $text = $this->bitcoinService->getRate();
                        $this->api->sendMessage($chatId, $text, replyToMessageId: $messageId);
                        break;
                }
            } catch (ServiceUnavailableException) {
                $this->api->sendMessage(
                    chatId: $chatId,
                    text: "Service Indisponible pour l'instant !",
                    replyToMessageId: $messageId
                );
            }
        } catch (InvalidArgumentException | \Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
