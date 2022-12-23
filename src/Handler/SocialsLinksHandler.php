<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\SocialsLinksCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

/**
 * class SocialsLinksHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class SocialsLinksHandler
{
    public function __construct(
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(SocialsLinksCommand $command): void
    {
        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: 'Suivez-nous sur les réseaux sociaux',
            replyToMessageId: $command->getReplyToMessageId(),
            replyMarkup: new InlineKeyboardMarkup(array_chunk([
                [
                    'text' => 'Twitter',
                    'url' => 'https://twitter.com/devscasttech',
                ],
                [
                    'text' => 'Linkedin',
                    'url' => 'https://cd.linkedin.com/company/devscast',
                ],
                [
                    'text' => 'Instagram',
                    'url' => 'https://www.instagram.com/devscast.tech',
                ],
                [
                    'text' => 'Facebook',
                    'url' => 'https://web.facebook.com/devscast.tech',
                ],
                [
                    'text' => 'Youtube',
                    'url' => 'https://www.youtube.com/channel/UCsvWpowwYtjfgS1BOcrX0fw',
                ],
                [
                    'text' => 'Tiktok',
                    'url' => 'https://www.tiktok.com/@devscast.tech',
                ],
            ], 2))
        );
    }
}
