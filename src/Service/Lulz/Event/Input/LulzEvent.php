<?php

declare(strict_types=1);

namespace App\Service\Lulz\Event\Input;

use App\Service\InputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class LulzEvent implements InputEventInterface
{
    public function __construct(private readonly array $update)
    {
    }

    public function __toString(): string
    {
        return <<< MESSAGE
Devscast JokeTime 😅 😂 🤣 :  \n
{$this->update['title']}
MESSAGE;
    }

    public function getUpdate(): string|array
    {
        return $this->update;
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-community');
    }

    public function getImageUrl(): string
    {
        return $this->update['image_url'];
    }
}
