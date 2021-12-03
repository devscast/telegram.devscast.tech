<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\PayloadInterface;
use App\Service\Telegram\Event\TelegramCommandFiredEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\Types\MessageEntity;

final class TelegramPayload implements PayloadInterface
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    public function process(Request $request): void
    {
        /** @var array $data */
        $data = json_decode(json: (string)$request->getContent(), associative: true);
        $update = Update::fromResponse($data);
        $message = $update->getMessage();

        if ($message && !empty($message->getEntities())) {
            foreach ($message->getEntities() as $entity) {
                $this->dispatchExtractedCommand($message, $entity);
            }
        }
    }

    private function dispatchExtractedCommand(Message $message, MessageEntity $entity): void
    {
        if ($entity->getType() === 'bot_command') {
            $command = $this->extractCommandFromMessage($message, $entity);
            $argument = $this->extractArgumentFromCommand($message, $command);

            // TODO: route commandes
            $this->dispatcher->dispatch(
                event: new TelegramCommandFiredEvent(
                    message: $message,
                    command: $command,
                    argument: $argument
                )
            );
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
