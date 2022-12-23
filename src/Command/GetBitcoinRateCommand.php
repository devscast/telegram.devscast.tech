<?php

declare(strict_types=1);

namespace App\Command;

use App\Telegram\BotReply;
use App\Telegram\BotTrigger;

/**
 * class GetBitcoinRateCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class GetBitcoinRateCommand extends AbstractCommand
{
    public const NAME = 'bitcoin';

    public const RESTRICTED = false;

    public const TRIGGERS = [BotTrigger::CHAT, BotTrigger::CLI];

    public const REPLY_MODE = BotReply::FROM_CHAT;

    public const CLI_DEVSCAST_CHAT = 'devscast-community';
}
