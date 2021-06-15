<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Telegram\CommandFiredEvent;
use App\Service\BitcoinService;
use App\Service\CommandService;
use App\Service\Covid19Service;
use App\Service\ServiceUnavailableException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * Class TelegramWebhookSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TelegramWebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private BotApi $api,
        private CommandService $commandService,
        private Covid19Service $covid19Service,
        private BitcoinService $bitcoinService,
        private LoggerInterface $logger,
    ) {
    }


    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            CommandFiredEvent::class => 'onCommandFired'
        ];
    }

    /**
     * @param CommandFiredEvent $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onCommandFired(CommandFiredEvent $event): void
    {
        $messageId = $event->getMessage()->getMessageId();
        $chatId = $event->getMessage()->getChat()->getId();

        try {
            try {
                switch ($event->getCommand()) {
                    case '/start@DevscastNotifierBot':
                    case '/start':
                        $text = $this->commandService->start();
                        $this->api->sendMessage($chatId, $text, replyToMessageId: $messageId);
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
