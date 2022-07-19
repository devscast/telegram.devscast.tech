<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Input;

use App\Service\InputEventInterface;

final class IssueEvent implements InputEventInterface
{
    public function __construct(
        private readonly array $update
    ) {
    }

    public function __toString(): string
    {
        $data = [];
        foreach ($this->update as $issue) {
            $title = sprintf('#%s %s', $issue['number'], $issue['title']);
            $assignee = $issue['assignee'] ? $issue['assignee']['login'] : 'bernard-ng';
            $data[] = <<< MESSAGE

🛠 **{$title}**
🚻 **{$assignee}**

MESSAGE;
        }

        $message = implode(' ', $data);
        return <<< MESSAGE
Il y a encore du travail, voici un petit rappel et les tâches de chacun
fermez l'issue sur github pour signaler que vous avez fini

{$message}

MESSAGE;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }
}
