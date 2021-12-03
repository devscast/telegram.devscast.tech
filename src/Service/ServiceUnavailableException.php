<?php

declare(strict_types=1);

namespace App\Service;

final class ServiceUnavailableException extends \Exception
{
    private function __construct(string $message = "", ?int $code = 0, \Throwable $previous = null)
    {
        /** @var int $code */
        parent::__construct($message, $code, $previous);
    }

    public static function fromException(\Exception|\Throwable $e): self
    {
        /** @var int $code */
        $code = $e->getCode();
        return new self($e->getMessage(), $code, $e->getPrevious());
    }
}
