<?php

declare(strict_types=1);

namespace App\Telegram;

/**
 * enum BotTrigger.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum BotTrigger: string
{
    case CLI = 'cli';
    case WEBHOOK = 'webhook';
    case CHAT = 'chat';
}
