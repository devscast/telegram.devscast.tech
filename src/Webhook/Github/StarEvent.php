<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Webhook\WebhookEventInterface;

/**
 * class StarEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class StarEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $starrer = $this->data['sender']['login'];
        $project = $this->data['repository']['name'];

        return sprintf(
            '✨ %s starred *%s*',
            $starrer,
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
