<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

/**
 * class PullRequestEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class PullRequestEvent implements OutputEventInterface
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

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }

    private function openedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été ouvert sur le projet %s par %s',
            $data['pull_request']['number'],
            $data['repository']['name'],
            $data['sender']['login']
        );
    }

    private function closedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été fermé sur le projet %s par %s',
            $data['pull_request']['number'],
            $data['repository']['name'],
            $data['sender']['login']
        );
    }

    private function assignedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été assigné à %s sur le projet %s',
            $data['pull_request']['number'],
            $data['assignee']['login'],
            $data['repository']['name']
        );
    }

    private function editedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été édité sur le projet %s par %s',
            $data['pull_request']['number'],
            $data['repository']['name'],
            $data['sender']['login']
        );
    }

    private function unassignedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été désassigné à %s sur le projet %s',
            $data['pull_request']['number'],
            $data['assignee']['login'],
            $data['repository']['name']
        );
    }

    private function reopenPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été réouvert sur le projet %s par %s',
            $data['pull_request']['number'],
            $data['repository']['name'],
            $data['sender']['login']
        );
    }

    private function reviewRequestedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été demandé en review à %s sur le projet %s',
            $data['pull_request']['number'],
            $data['requested_reviewer']['login'],
            $data['repository']['name']
        );
    }

    private function milestonedPullRequest(array $data): string
    {
        return sprintf(
            '📌 pull_request[%s] a été ajouté au milestone %s sur le projet %s',
            $data['pull_request']['number'],
            $data['milestone']['title'],
            $data['repository']['name']
        );
    }
}
