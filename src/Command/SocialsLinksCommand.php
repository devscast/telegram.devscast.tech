<?php

declare(strict_types=1);

namespace App\Command;

use App\Telegram\BotReply;
use App\Telegram\BotTrigger;

/**
 * class SocialsLinksCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class SocialsLinksCommand extends AbstractCommand
{
    public const NAME = 'socials_links';

    public const RESTRICTED = false;

    public const TRIGGERS = [BotTrigger::CHAT];

    public const REPLY_MODE = BotReply::CURRENT_CHAT;

    public const CLI_DEVSCAST_CHAT = 'devscast-community';
}
