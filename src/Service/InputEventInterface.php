<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Telegram\TelegramTarget;

/**
 * interface InputEventInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface InputEventInterface extends \Stringable
{
    public function __toString(): string;

    public function getUpdate(): string|array;

    public function getTarget(): TelegramTarget;
}
