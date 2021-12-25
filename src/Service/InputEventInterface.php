<?php

declare(strict_types=1);

namespace App\Service;

interface InputEventInterface extends \Stringable
{
    public function __toString(): string;

    public function getUpdate(): string|array;
}
