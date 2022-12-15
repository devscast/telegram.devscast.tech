<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class ForkEvent implements OutputEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $forker = $this->data['forkee']['owner']['login'];
        $project = $this->data['repository']['name'];

        return sprintf(
            '🔥 %s forked %s',
            $forker,
            $project,
        );
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }
}
