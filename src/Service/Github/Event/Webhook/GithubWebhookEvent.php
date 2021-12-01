<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Webhook;

abstract class GithubWebhookEvent
{
    protected string $name;

    public function __construct(
        private string $guid,
        private array  $data
    ) {
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
