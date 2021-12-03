<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Webhook;

final class GithubPingEvent extends GithubWebhookEvent
{
    protected string $name = 'ping';
}
