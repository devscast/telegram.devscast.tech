<?php

declare(strict_types=1);

namespace App\Command;

use App\Telegram\BotReply;
use App\Telegram\BotTrigger;

/**
 * class GetDevscastLatestPostCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class GetDevscastLatestPostCommand extends AbstractCommand
{
    public const NAME = 'posts';

    public const RESTRICTED = false;

    public const TRIGGERS = [BotTrigger::CHAT, BotTrigger::CLI];

    public const REPLY_MODE = BotReply::CURRENT_CHAT;

    public const CLI_DEVSCAST_CHAT = 'devscast-community';
}
