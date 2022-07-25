<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Telegram\TelegramTarget;

interface OutputEventInterface extends \Stringable
{
    public function __toString(): string;

    public function getTarget(): TelegramTarget;
}
