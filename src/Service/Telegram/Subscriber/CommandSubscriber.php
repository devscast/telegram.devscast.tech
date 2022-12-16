<?php

declare(strict_types=1);

namespace App\Service\Telegram\Subscriber;

use App\Service\Telegram\Event\CommandEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

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
                    $this->api->sendMessage($chatId, "Hi I'm Devscast Bot", replyToMessageId: $messageId);
                    break;
                case '/rules@DevscastNotifierBot':
                case '/rules':
                    $this->api->sendMessage($chatId, $this->getRules(), replyToMessageId: $messageId);
                    break;
                case '/socials@DevscastNotifierBot':
                case '/socials':
                    $this->api->sendMessage(
                        chatId: $chatId,
                        text: 'Suivez-nous sur les réseaux sociaux',
                        replyToMessageId: $messageId,
                        replyMarkup: $this->getSocials()
                    );
                    break;
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    private function getRules(): string
    {
        return <<< RULES
Quelques règles : 
- Bonne humeur et bienveillance
- Nous sommes tous là pour apprendre
- pas de spam
- pas d’insultes etc…
- aucun message publicitaire 

Tout comportement suspect sera ban
RULES;
    }

    private function getSocials(): InlineKeyboardMarkup
    {
        return new InlineKeyboardMarkup(array_chunk([
            ['text' => 'Twitter', 'url' => 'https://twitter.com/devscasttech'],
            ['text' => 'Linkedin', 'url' => 'https://www.instagram.com/devscast.tech'],
            ['text' => 'Instagram', 'url' => 'https://www.instagram.com/devscast.tech'],
            ['text' => 'Facebook', 'url' => 'https://web.facebook.com/devscast.tech'],
            ['text' => 'Youtube', 'url' => 'https://www.youtube.com/channel/UCsvWpowwYtjfgS1BOcrX0fw'],
            ['text' => 'Tiktok', 'url' => 'https://www.tiktok.com/@devscast.tech']
        ], 2));
    }
}
