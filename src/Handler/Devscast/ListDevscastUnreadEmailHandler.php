<?php

declare(strict_types=1);

namespace App\Handler\Devscast;

use App\Command\UnreadEmailCommand;
use App\Event\UnreadEmailEvent;
use Ddeboer\Imap\ConnectionInterface;
use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\Server;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * class UnreadEmailHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class UnreadEmailHandler
{
    private readonly ConnectionInterface $connection;

    public function __construct(
        private readonly EventDispatcherInterface $dispatcher
    ) {
        $server = new Server(
            hostname: $_ENV['IMAP_HOST'],
            port: $_ENV['IMAP_PORT'],
            flags: $_ENV['APP_ENV'] === 'dev' ? '/imap/ssl/novalidate-cert' : '/imap/ssl/validate-cert'
        );

        $this->connection = $server->authenticate(
            username: $_ENV['IMAP_USER'],
            password: $_ENV['IMAP_PASSWORD']
        );
    }

    public function __invoke(UnreadEmailCommand $command): void
    {
        $update = $this->inbox();
        $this->dispatcher->dispatch(new UnreadEmailEvent($update));
    }

    public function inbox(): iterable
    {
        $inbox = $this->connection->getMailbox('INBOX');
        return $inbox->getMessages(
            search: new Unseen(),
            sortCriteria: \SORTDATE, // https://php.net/manual/en/imap.constants.php
            descending: true
        );
    }
}
