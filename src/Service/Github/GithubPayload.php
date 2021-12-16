<?php

declare(strict_types=1);

namespace App\Service\Github;

use App\Service\Github\Event\Webhook\GithubIssuesEvent;
use App\Service\Github\Event\Webhook\GithubPingEvent;
use App\Service\Github\Event\Webhook\GithubPushEvent;
use App\Service\PayloadInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

final class GithubPayload implements PayloadInterface
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    public function process(Request $request): void
    {
        /** @var array $data */
        $data = json_decode(json: (string)$request->getContent(), associative: true) ?: [];
        $event = (string) $request->headers->get('X-GitHub-Event', '');
        $guid = (string) $request->headers->get('X-GitHub-Delivery', '');

        $event = match ($event) {
            'ping' => new GithubPingEvent($guid, $data),
            'issues' => new GithubIssuesEvent($guid, $data),
            'push' => new GithubPushEvent($guid, $data),
            default => null
        };

        if ($event !== null) {
            $this->dispatcher->dispatch($event);
        }
    }
}
