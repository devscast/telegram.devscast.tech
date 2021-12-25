<?php

declare(strict_types=1);

namespace App\Service\Devscast\Event;

interface DevscastWebhookEvent extends \Stringable {
    public function __toString(): string;
}
