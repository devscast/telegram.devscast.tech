<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Github\Event\Output\ForkEvent;
use App\Service\Github\Event\Output\IssuesEvent;
use App\Service\Github\Event\Output\PingEvent;
use App\Service\Github\Event\Output\PullRequestEvent;
use App\Service\Github\Event\Output\PullRequestReviewEvent;
use App\Service\Github\Event\Output\PushEvent;
use App\Service\Github\Event\Output\StarEvent;
use App\Service\Github\Event\Output\StatusEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * class GithubWebhookController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final class GithubWebhookController
{
    #[Route('/webhook/github', name: 'app_webhook_github', methods: ['POST'])]
    public function index(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        /** @var array $data */
        $data = json_decode(json: (string) $request->getContent(), associative: true) ?: [];
        $event = (string) $request->headers->get('X-GitHub-Event', '');

        $event = match ($event) {
            'ping' => new PingEvent($data),
            'issues' => new IssuesEvent($data),
            'push' => new PushEvent($data),
            'pull_request' => new PullRequestEvent($data),
            'pull_request_review' => new PullRequestReviewEvent($data),
            'fork' => new ForkEvent($data),
            'star' => new StarEvent($data),
            'status' => new StatusEvent($data),
            default => null
        };

        if ($event !== null) {
            $dispatcher->dispatch($event);
        }

        return new Response(null, Response::HTTP_OK);
    }
}
