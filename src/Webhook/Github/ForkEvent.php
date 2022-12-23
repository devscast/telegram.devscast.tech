<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Telegram\Str;
use App\Webhook\WebhookEventInterface;

/**
 * class ForkEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ForkEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $forker = Str::escape($this->data['forkee']['owner']['login']);
        $project = Str::escape($this->data['repository']['name']);

        return sprintf(
            '🍴 *%s* forked *%s*',
            $forker,
            $project,
        );
    }

    public function getChatId(): ChatId
    {
        return new ChatId('devscast-team');
    }

    public function getUpdate(): string|iterable
    {
        return $this->data;
    }
}
