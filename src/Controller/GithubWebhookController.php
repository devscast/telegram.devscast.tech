<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Github\Event\Output\IssuesEvent;
use App\Service\Github\Event\Output\PingEvent;
use App\Service\Github\Event\Output\PushEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class GithubWebhookController
{
    #[Route('/webhook/github', name: 'app_webhook_github', methods: ['POST'])]
    public function index(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        /** @var array $data */
        $data = json_decode(json: (string)$request->getContent(), associative: true) ?: [];
        $event = (string) $request->headers->get('X-GitHub-Event', '');

        $event = match ($event) {
            'ping' => new PingEvent( $data),
            'issues' => new IssuesEvent($data),
            'push' => new PushEvent($data),
            default => null
        };

        if ($event !== null) {
            $dispatcher->dispatch($event);
        }

        return new Response(null, Response::HTTP_OK);
    }
}
