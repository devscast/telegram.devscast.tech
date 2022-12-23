<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\AboutCommand;
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
*Devscast*, 

Bâtie autour d'une *communauté* nous construisons un écosystème d'apprentissage,
accompagnement et recrutement de développeur grâce à la production de contenu dans le contexte local,
et activités tech ayant un fort impact.

Le but:
- Communiquer, discuter des nouvelles et des technologies.
- Organiser des réunions, inviter des intervenants et des experts.
- Créer et soutenir des projets de logiciels libres (opensource)
- Aider à la formation des nouveaux membres.

Nos plateformes:
- [Notre site](https://devscast.tech)
- [Notre Github](https://github.com/devscast)
- [Communauté (WIP...)](https://devscast.org)

Nous Contactez:
- contact@devscast.tech
ABOUT;

        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: $about,
            parseMode: 'MarkdownV2',
            replyToMessageId: $command->getReplyToMessageId()
        );
    }
}
