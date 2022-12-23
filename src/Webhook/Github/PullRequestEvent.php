<?php

declare(strict_types=1);

namespace App\Webhook\Github;

use App\Telegram\ChatId;
use App\Telegram\Str;
use App\Webhook\WebhookEventInterface;

/**
 * class PullRequestEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PullRequestEvent implements WebhookEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        return match ($this->data['action']) {
            'opened' => $this->openedPullRequest($this->data),
            'closed' => $this->closedPullRequest($this->data),
            'assigned' => $this->assignedPullRequest($this->data),
            'edited' => $this->editedPullRequest($this->data),
            'unassigned' => $this->unassignedPullRequest($this->data),
            'reopened' => $this->reopenPullRequest($this->data),
            'review_requested' => $this->reviewRequestedPullRequest($this->data),
            'milestoned' => $this->milestonedPullRequest($this->data),
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

    private function openedPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été ouvert sur le projet %s par %s',
            $data['pull_request']['number'],
            Str::escape($data['repository']['name']),
            Str::escape($data['sender']['login'])
        );
    }

    private function closedPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été fermé sur le projet %s par %s',
            $data['pull_request']['number'],
            Str::escape($data['repository']['name']),
            Str::escape($data['sender']['login'])
        );
    }

    private function assignedPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été assigné à %s sur le projet %s',
            $data['pull_request']['number'],
            $data['assignee']['login'],
            $data['repository']['name']
        );
    }

    private function editedPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été édité sur le projet %s par %s',
            $data['pull_request']['number'],
            Str::escape($data['repository']['name']),
            Str::escape($data['sender']['login'])
        );
    }

    private function unassignedPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été désassigné à %s sur le projet %s',
            $data['pull_request']['number'],
            Str::escape($data['assignee']['login']),
            Str::escape($data['repository']['name'])
        );
    }

    private function reopenPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été réouvert sur le projet %s par %s',
            $data['pull_request']['number'],
            Str::escape($data['repository']['name']),
            Str::escape($data['sender']['login'])
        );
    }

    private function reviewRequestedPullRequest(array $data): string
    {
        return sprintf(
            '⬆ pull_request[%d] a été demandé en review à %s sur le projet %s',
            $data['pull_request']['number'],
            Str::escape($data['requested_reviewer']['login']),
            Str::escape($data['repository']['name'])
        );
    }

    private function milestonedPullRequest(array $data): string
    {
        return sprintf(
            '⬆pull_request[%d] a été ajouté au milestone %s sur le projet %s',
            $data['pull_request']['number'],
            Str::escape($data['milestone']['title']),
            Str::escape($data['repository']['name'])
        );
    }
}
