<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Telegram\Str;
use App\Webhook\WebhookEventInterface;

/**
 * class PushEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PushEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $commit = substr(strval($this->data['after']), 0, 8);
        $project = Str::escape($this->data['repository']['name']);
        $pusher = Str::escape($this->data['pusher']['name']);
        $ref = Str::escape(str_replace('refs/heads/', '', $this->data['ref']));
        $message = Str::escape($this->data['head_commit']['message']);

        if ($commit === '00000000') {
            return sprintf(
                '🔥 *%s* a supprimé la branche *%s* sur *%s*',
                $pusher,
                $ref,
                $project
            );
        }
        return sprintf(
            '🔥 *%s* a fait un push sur *%s* commit *%s*, branche *%s* \: %s',
            $pusher,
            $project,
            $commit,
            $ref,
            $message,
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
