<?php

declare(strict_types=1);

namespace App;

/**
 * enum BotTrigger.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
enum BotTrigger: string
{
    case CRON = 'cron';
    case WEBHOOK = 'webhook';
    case CHAT = 'chat';
}
