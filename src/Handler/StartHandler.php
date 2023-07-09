<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\StartCommand;
use App\Telegram\Str;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class StartHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class StartHandler
{
    public function __construct(
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(StartCommand $command): void
    {
        $menu = <<< MENU
Hello, I'm DevscastNotifierBot

[everyone]
/start - affiche ce menu
/about - à propos de devscast
/socials - nos réseaux sociaux
/rules - les règles de la communauté
/posts - nos derniers articles
/podcasts - nos derniers podcasts
/hackernews - 10 stories de hackernews
/bitcoin - le cours du bitcoin
/covid - les dernières mises à jour sur le covid-19

[admin]
/joieducodes - envoie meme de programmation
/quiz - crée quiz de programmation
/emails - derniers emails devscast
/issues - les issues sur devscast
MENU;

        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: Str::escape($menu),
            parseMode: 'MarkdownV2',
            replyToMessageId: $command->getReplyToMessageId(),
            messageThreadId: $command->getMessage()?->getMessageThreadId()
        );
    }
}
