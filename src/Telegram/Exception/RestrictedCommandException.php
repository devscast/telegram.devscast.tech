<?php

declare(strict_types=1);

namespace App\Telegram\Exception;

/**
 * class RestrictedCommandException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RestrictedCommandException extends \DomainException
{
    /**
     * @var string
     */
    protected $message = 'Cette commande est réservée aux administrateurs';
}
