<?php

declare(strict_types=1);

namespace App\Telegram;

use TelegramBot\Api\Types\Message;

/**
 * class BotCommandEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class BotCommandEvent
{
    public function __construct(
        private readonly Message $message,
        private readonly string $command,
        private readonly string $argument
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
