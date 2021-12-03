<?php

declare(strict_types=1);

namespace App\Service\Telegram\Event;

use TelegramBot\Api\Types\Message;

final class TelegramCommandFiredEvent
{
    public function __construct(
        private Message $message,
        private string $command,
        private string $argument
    ) {
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getArgument(): string
    {
        return $this->argument;
    }
}
