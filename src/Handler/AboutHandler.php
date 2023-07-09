<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\AboutCommand;
use App\Telegram\Str;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class AboutHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class AboutHandler
{
    public function __construct(
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(AboutCommand $command): void
    {
        $about = <<< ABOUT
Devscast Community, 

Un espace pour les développeurs afin d'apprendre, de grandir et de résoudre des problèmes ensemble

Devscast, créé pour construire une communauté de développeurs compétents qui peuvent travailler ensemble pour résoudre des problèmes sociaux à grande échelle.

Le but:
- Communiquer, discuter des nouvelles et des technologies.
- Organiser des réunions, inviter des intervenants et des experts.
- Créer et soutenir des projets de logiciels libres (opensource)
- Aider à la formation des nouveaux membres.

Nos plateformes:
- [Notre site](https://devscast.tech)
- [Notre Github](https://github.com/devscast)
- [Notre Plateforme](https://devscast.org)

Nous Contactez:
- community@devscast.org
ABOUT;

        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: Str::escape($about),
            parseMode: 'MarkdownV2',
            replyToMessageId: $command->getReplyToMessageId(),
            messageThreadId: $command->getMessage()?->getMessageThreadId()
        );
    }
}
