<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\AboutCommand;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;

/**
 * class AboutHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class AboutHandler
{
    public function __construct(private readonly BotApi $api)
    {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(AboutCommand $command): void
    {
        $chatId = $command->getMessage()?->getChat()->getId();
        $messageId = $command->getMessage()?->getMessageId();

        $rules = <<< RULES
Quelques règles :
 
- Bonne humeur et bienveillance
- Nous sommes tous là pour apprendre
- Pas de spam (message non relatif à la tech)
- Pas d’insultes etc..., les échanges se font dans le respect
- Aucun message publicitaire et autopromotion

Tout membre ayant un comportement suspect sera ban
RULES;

        $this->api->sendMessage($chatId, $rules, replyToMessageId: $messageId);
    }
}
