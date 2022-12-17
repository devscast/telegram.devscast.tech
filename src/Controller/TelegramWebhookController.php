<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Telegram\Event\CommandEvent;
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
final class TelegramWebhookController
{
    public function __construct(private readonly EventDispatcherInterface $dispatcher)
    {
    }

    #[Route('/webhook/telegram', name: 'app_webhook_telegram', methods: ['POST'])]
    public function index(Request $request): Response
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
            $this->dispatcher->dispatch(new CommandEvent($message, $command, $argument));
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
