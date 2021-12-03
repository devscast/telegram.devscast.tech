<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface PayloadInterface
{
    public function process(Request $request): void;
}
