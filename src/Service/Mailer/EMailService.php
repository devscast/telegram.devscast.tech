<?php

declare(strict_types=1);

namespace App\Service\Mailer;

use App\Service\Formatter\EMailMessageFormatter;
use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\ConnectionInterface;

/**
 * Class EMailService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EMailService
{
    private ConnectionInterface $connection;
    private EMailMessageFormatter $formatter;

    /**
     * MailerService constructor.
     * @param ConnectionInterface $connection
     * @param EMailMessageFormatter $formatter
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(ConnectionInterface $connection, EMailMessageFormatter $formatter)
    {
        $this->connection = $connection;
        $this->formatter = $formatter;
    }

    /**
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function inbox(): array
    {
        $inbox = $this->connection->getMailbox("INBOX");
        $messages = $inbox->getMessages(new Unseen(), \SORTDATE, true);
        return $this->formatter->format($messages);
    }
}
