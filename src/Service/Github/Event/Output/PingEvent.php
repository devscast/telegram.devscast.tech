<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;

final class PingEvent implements OutputEventInterface
{
    public function __construct(
        protected array  $data
    ) {
    }

    public function __toString(): string
    {
        return sprintf('👉🏾 Github ping : %s', $this->data['zen']);
    }
}
