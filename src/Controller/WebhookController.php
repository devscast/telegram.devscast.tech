<?php

declare(strict_types=1);

namespace App\Controller;

use App\Telegram\BotCommandEvent;
use App\Webhook\Devscast\ContactSubmittedEvent;
use App\Webhook\Devscast\ContentCreatedEvent;
use App\Webhook\Github\ForkEvent;
use App\Webhook\Github\IssuesEvent;
use App\Webhook\Github\PingEvent;
use App\Webhook\Github\PullRequestEvent;
use App\Webhook\Github\PullRequestReviewEvent;
use App\Webhook\Github\PushEvent;
use App\Webhook\Github\StarEvent;
use App\Webhook\Github\StatusEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\MessageEntity;
use TelegramBot\Api\Types\Update;

/**
 * class TelegramWebhookController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
#[Route('/webhook', name: 'app_webhook_', methods: ['POST'])]
final class WebhookController
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    #[Route('/devscast', name: 'devscast')]
    public function devscast(Request $request): Response
    {
        /** @var array $data */
        $data = json_decode(json: (string) $request->getContent(), associative: true);
        $event = (string) $request->headers->get('X-Devscast-Event', '');

        $event = match ($event) {
            'contact_form_submitted' => ContactSubmittedEvent::fromArray($data),
            'content_created' => ContentCreatedEvent::fromArray($data),
            default => null
        };

        if ($event !== null) {
            $this->dispatcher->dispatch($event);
        }

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/github', name: 'github')]
    public function github(Request $request): Response
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
            $this->dispatcher->dispatch($event);
        }

        return new Response(null, Response::HTTP_OK);
    }

    #[Route('/telegram', name: 'telegram')]
    public function telegram(Request $request): Response
    {
        /** @var array $data */
        $data = json_decode(json: (string) $request->getContent(), associative: true);
        $update = Update::fromResponse($data);
        $message = $update->getMessage();

        if ($message && ! empty($message->getEntities())) {
            foreach ($message->getEntities() as $entity) {
                $this->dispatchExtractedCommand($message, $entity);
            }
        }

        return new Response(null, Response::HTTP_OK);
    }

    private function dispatchExtractedCommand(Message $message, MessageEntity $entity): void
    {
        if ($entity->getType() === 'bot_command') {
            $command = $this->extractCommandFromMessage($message, $entity);
            $argument = $this->extractArgumentFromCommand($message, $command);
            $this->dispatcher->dispatch(new BotCommandEvent($message, $command, $argument));
        }
    }

    private function extractCommandFromMessage(Message $message, MessageEntity $entity): string
    {
        return trim(
            string: substr(
                string: $message->getText(),
                offset: $entity->getOffset(),
                length: $entity->getLength()
            )
        );
    }

    private function extractArgumentFromCommand(Message $message, string $command): string
    {
        return trim(
            string: str_ireplace(
                search: $command,
                replace: '',
                subject: $message->getText()
            )
        );
    }
}
