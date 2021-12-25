<?php

declare(strict_types=1);

namespace App\Service;

interface OutputEventInterface extends \Stringable
{
    public function __toString(): string;
}
