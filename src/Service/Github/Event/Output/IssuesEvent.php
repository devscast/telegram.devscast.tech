<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class IssuesEvent implements OutputEventInterface
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

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }

    private function assignedIssue(array $data): string
    {
        $title = sprintf('#%s %s', $data['issue']['number'], $data['issue']['title']);
        $assignee = $data['assignee']['login'];
        $project = $data['repository']['name'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
👨🏽‍🔧 Assignation de tâche : {$project}

{$title}

👨‍💻 {$assignee}
🕒 {$date}
MESSAGE;
    }

    private function openedIssue(array $data): string
    {
        $title = sprintf('#%s %s', $data['issue']['number'], $data['issue']['title']);
        $project = $data['repository']['name'];
        $sender = $data['sender']['login'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
👨🏽‍🔧 Nouvelle Issue : {$project}

{$title}

Créée par {$sender}
🕒 {$date}
MESSAGE;
    }

    private function closedIssue(array $data): string
    {
        $title = sprintf('#%s %s', $data['issue']['number'], $data['issue']['title']);
        $project = $data['repository']['name'];
        $sender = $data['sender']['login'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
👨🏽‍🔧 Fermeture Issue : {$project}

{$title}

Créée par {$sender}
🕒 {$date}
MESSAGE;
    }
}
