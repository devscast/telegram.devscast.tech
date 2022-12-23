<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Telegram\Str;
use App\Webhook\WebhookEventInterface;

/**
 * class StatusEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class StatusEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        if ($this->data['state'] === 'failure' || $this->data['state'] === 'error') {
            $status = $this->data['state'];
            $project = Str::escape($this->data['repository']['name']);
            $commit = $this->data['sha'];

            return sprintf(
                '(🔴 %s) %s - commit *%s*',
                $status,
                $project,
                $commit,
            );
        }

        return '';
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
