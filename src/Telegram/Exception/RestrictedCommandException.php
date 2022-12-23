<?php

declare(strict_types=1);

namespace App\Telegram;

/**
 * class RestrictedCommandException.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class RestrictedCommandException extends \DomainException
{
    protected $message = 'Cette commande est réservée aux administrateurs';
}
