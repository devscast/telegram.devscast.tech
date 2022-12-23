<?php

declare(strict_types=1);

namespace App\Telegram\Exception;

/**
 * class ServiceUnavailableException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ServiceUnavailableException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Service indisponible';

    public static function fromException(\Throwable $e): self
    {
        return new self($e->getMessage(), $e->getCode(), $e->getPrevious());
    }
}
