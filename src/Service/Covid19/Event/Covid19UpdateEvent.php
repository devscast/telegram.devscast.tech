<?php

declare(strict_types=1);

namespace App\Service\Covid19\Event;

use App\Service\UpdateEventInterface;

class Covid19UpdateEvent implements UpdateEventInterface
{
    public function __construct(private string $update)
    {
    }

    public function getUpdate(): string
    {
        return $this->update;
    }
}
