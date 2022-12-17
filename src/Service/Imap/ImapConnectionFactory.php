<?php

declare(strict_types=1);

namespace App\Service\Imap;

use Ddeboer\Imap\ConnectionInterface;
use Ddeboer\Imap\Server;

/**
 * class ImapConnectionFactory.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ImapConnectionFactory
{
    public static function createInstance(): ConnectionInterface
    {
        $server = new Server(
            hostname: $_ENV['IMAP_HOST'],
            port: $_ENV['IMAP_PORT'],
            flags: $_ENV['APP_ENV'] === 'dev' ? '/imap/ssl/novalidate-cert' : '/imap/ssl/validate-cert'
        );

        return $server->authenticate(
            username: $_ENV['IMAP_USER'],
            password: $_ENV['IMAP_PASSWORD']
        );
    }
}
