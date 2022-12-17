<?php

declare(strict_types=1);

namespace App\Service;

/**
 * class ServiceUnavailableException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ServiceUnavailableException extends \Exception
{
    public function __construct(string $message = '', ?int $code = 0, \Throwable $previous = null)
    {
        /** @var int $code */
        parent::__construct($message, $code, $previous);
    }

    public static function fromException(\Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e->getPrevious());
    }
}
