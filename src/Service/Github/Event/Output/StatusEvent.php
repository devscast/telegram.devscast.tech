<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class StatusEvent implements OutputEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        if ($this->data['state'] === 'failure' || $this->data['state'] === 'error') {
            $status = $this->data['state'];
            $project = $this->data['repository']['name'];
            $commit = $this->data['sha'];

            return sprintf(
                '[🔴 %s] %s %s',
                $status,
                $project,
                $commit,
            );
        }

        return '';
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }
}
