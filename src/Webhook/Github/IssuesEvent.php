<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Telegram\Str;
use App\Webhook\WebhookEventInterface;

/**
 * class IssuesEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class IssuesEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        return match ($this->data['action']) {
            'assigned' => $this->assignedIssue($this->data),
            'opened' => $this->openedIssue($this->data),
            'closed' => $this->closedIssue($this->data),
            default => ''
        };
    }

    public function getChatId(): ChatId
    {
        return new ChatId('devscast-team');
    }

    public function getUpdate(): string|iterable
    {
        return $this->data;
    }

    private function assignedIssue(array $data): string
    {
        $title = $data['issue']['number'];
        $assignee = Str::escape($data['assignee']['login']);
        $project = Str::escape($data['repository']['name']);

        return sprintf(
            '📌 issues[%d] est assigné à *%s* sur le projet *%s*',
            $title,
            $assignee,
            $project
        );
    }

    private function openedIssue(array $data): string
    {
        $title = $data['issue']['number'];
        $project = Str::escape($data['repository']['name']);
        $sender = Str::escape($data['sender']['login']);

        return sprintf(
            '📌 issues[%d] a été ouvert sur le projet *%s* par *%s*',
            $title,
            $project,
            $sender
        );
    }

    private function closedIssue(array $data): string
    {
        $title = $data['issue']['number'];
        $project = Str::escape($data['repository']['name']);
        $sender = Str::escape($data['sender']['login']);

        return sprintf(
            '📌 issues[%d] a été fermé sur le projet *%s* par *%s*',
            $title,
            $project,
            $sender
        );
    }
}
