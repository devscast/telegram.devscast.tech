<?php

declare(strict_types=1);

namespace App\Service\Bitcoin\Event;

use App\Service\UpdateEventInterface;

final class BitcoinUpdateEvent implements UpdateEventInterface
{
    public function __construct(private string $update)
    {
    }

    public function getUpdate(): string
    {
        return $this->update;
    }
}
