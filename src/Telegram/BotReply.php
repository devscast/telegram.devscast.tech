<?php

declare(strict_types=1);

namespace App;

/**
 * class BotReply.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum BotReply: string
{
    case FROM_CHAT = 'dm';
    case CURRENT_CHAT = 'current';
}
