<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\InputEventInterface;
use App\Service\OutputEventInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): InputEventInterface|OutputEventInterface;
}
