<?php

declare(strict_types=1);

namespace App\Command;

use App\Telegram\BotReply;
use App\Telegram\BotTrigger;

/**
 * class AboutCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class AboutCommand extends AbstractCommand
{
    public const NAME = 'about';

    public const RESTRICTED = false;

    public const TRIGGERS = [BotTrigger::CHAT];

    public const REPLY_MODE = BotReply::CURRENT_CHAT;

    public const CLI_DEVSCAST_CHAT = 'devscast-community';
}
