<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use TelegramBot\Api\Types\Message;

interface CommandInterface
{
    public function getMessage(): ?Message;

    public function getArgument(): ?string;
}
