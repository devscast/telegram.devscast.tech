<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class StarEvent implements OutputEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $starrer = $this->data['sender']['login'];
        $project = $this->data['repository']['name'];

        return sprintf(
            '🔥 %s starred %s',
            $starrer,
            $project,
        );
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }
}
