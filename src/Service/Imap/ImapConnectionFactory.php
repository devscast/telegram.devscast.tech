<?php

declare(strict_types=1);

namespace App\Service\Imap;

use Ddeboer\Imap\ConnectionInterface;
use Ddeboer\Imap\Server;

class ImapConnectionFactory
{
    public static function createInstance(): ConnectionInterface
    {
        $flags = $_ENV['APP_ENV'] === 'dev' ? '/imap/ssl/novalidate-cert' : '/imap/ssl/validate-cert';
        $server = new Server($_ENV['IMAP_HOST'], $_ENV['IMAP_PORT'], $flags);
        return $server->authenticate($_ENV['IMAP_USER'], $_ENV['IMAP_PASSWORD']);
    }
}
