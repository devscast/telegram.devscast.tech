<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Service\Formatter\EMailMessageFormatter;
use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\ConnectionInterface;

/**
 * Class EMailService
 * @package App\Service
 * @author bernard-ng <bernard@devscast.tech>
 */
class EMailService
{
    public function __construct(
        private ConnectionInterface $connection,
        private EMailMessageFormatter $formatter
    ) {
    }

    /**
     * @return array
     * @author bernard-ng <bernard@devscast.tech>
     */
    public function inbox(): array
    {
        $inbox = $this->connection->getMailbox("INBOX");
        $messages = $inbox->getMessages(new Unseen(), \SORTDATE, true);
        return $this->formatter->format($messages);
    }
}
