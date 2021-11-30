<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;

/**
 * Class ServiceUnavailableException
 * @package App\Service
 * @author bernard-ng <bernard@devscast.tech>
 */
class ServiceUnavailableException extends \Exception
{
    private function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromException(\Exception $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e->getPrevious());
    }
}
