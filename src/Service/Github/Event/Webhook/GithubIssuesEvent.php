<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Webhook;

final class GithubIssuesEvent extends GithubWebhookEvent
{
    protected string $name = "issues";
}
