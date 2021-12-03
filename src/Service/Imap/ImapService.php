<?php

declare(strict_types=1);

namespace App\Service\Imap;

use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\ConnectionInterface;

final class ImapService
{
    public function __construct(
        private ConnectionInterface  $connection,
        private ImapMessageFormatter $formatter
    ) {
    }

    public function inbox(): array
    {
        $inbox = $this->connection->getMailbox("INBOX");
        $messages = $inbox->getMessages(new Unseen(), \SORTDATE, true);
        return $this->formatter->format($messages);
    }
}
