<?php

declare(strict_types=1);

namespace App\Webhook;

use App\Telegram\ChatId;

/**
 * interface InputEventInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface WebhookEventInterface
{
    public function __toString(): string;

    public function getUpdate(): string|iterable;

    public function getChatId(): ChatId;
}
