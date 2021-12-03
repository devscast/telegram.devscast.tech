<?php

declare(strict_types=1);

namespace App\Service\Github\Event;

use App\Service\UpdateEventInterface;

final class GithubIssueUpdateEvent implements UpdateEventInterface
{
    public function __construct(private string $update)
    {
    }

    public function getUpdate(): string
    {
        return $this->update;
    }
}
