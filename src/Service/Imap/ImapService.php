<?php

declare(strict_types=1);

namespace App\Service\Imap;

use Ddeboer\Imap\ConnectionInterface;
use Ddeboer\Imap\Search\Flag\Unseen;

/**
 * class ImapService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ImapService
{
    public function __construct(
        private readonly ConnectionInterface $connection,
    ) {
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
