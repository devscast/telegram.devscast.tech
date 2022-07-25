<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Telegram\TelegramTarget;

interface InputEventInterface extends \Stringable
{
    public function __toString(): string;

    public function getUpdate(): string|array;

    public function getTarget(): TelegramTarget;
}
