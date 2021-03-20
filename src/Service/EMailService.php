<?php

declare(strict_types=1);

namespace App\Service;

use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\ConnectionInterface;

/**
 * Class EMailService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EMailService
{
    private ConnectionInterface $connection;

    /**
     * MailerService constructor.
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct()
    {
        $flags = $_ENV['APP_ENV'] === 'dev' ? '/imap/ssl/novalidate-cert' : '/imap/ssl/validate-cert';
        $server = new Server($_ENV['IMAP_HOST'], $_ENV['IMAP_PORT'], $flags);
        $this->connection = $server->authenticate($_ENV['IMAP_USER'], $_ENV['IMAP_PASSWORD']);
    }

    /**
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function inbox(): array
    {
        $inbox = $this->connection->getMailbox("INBOX");
        $messages = $inbox->getMessages(new Unseen(), \SORTDATE, true);
        $data = [];

        foreach ($messages as $message) {

            $body = $message->getBodyText() ?
                $message->getBodyText() :
                ($message->getBodyHtml() ? strip_tags($message->getBodyHtml()) : '⁉️ Message Vide');

            if (mb_strlen($body) > 200) {
                $space = strripos($body, " ", 0);
                $body = substr($body, 0, $space ? $space : 200) . '...';
            }

            $data[] = <<< MESSAGE
📩 **{$message->getFrom()->getFullAddress()}**
**{$message->getSubject()}**

{$body}

📬 **{$message->getTo()[0]->getFullAddress()}**
📪 **{$message->getDate()->format('d M Y H:i')}**
Prochain rappel dans 6h
MESSAGE;
        }

        return $data;
    }
}
