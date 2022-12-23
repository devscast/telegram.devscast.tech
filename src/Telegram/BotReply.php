<?php

declare(strict_types=1);

namespace App\Telegram;

/**
 * class BotReply.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum BotReply: string
{
    case FROM_CHAT = 'from_chat';
    case CURRENT_CHAT = 'current_chat';
}
