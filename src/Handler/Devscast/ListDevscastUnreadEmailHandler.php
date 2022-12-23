<?php

declare(strict_types=1);

namespace App\Handler\Devscast;

use App\Command\ListDevscastUnreadEmailCommand;
use App\Telegram\Str;
use Ddeboer\Imap\ConnectionInterface;
use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\Server;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class UnreadEmailHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class ListDevscastUnreadEmailHandler
{
    private readonly ConnectionInterface $connection;

    public function __construct(
        private readonly BotApi $api
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

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(ListDevscastUnreadEmailCommand $command): void
    {
        $update = $this->getUpdate();

        $data = '';
        foreach ($update as $message) {
            $body = $message->getBodyText() ? $message->getBodyText() :
                ($message->getBodyHtml() ? strip_tags($message->getBodyHtml()) : '⁉️ Message Vide');

            if (mb_strlen($body) > 200) {
                $space = strripos($body, ' ', 0);
                $body = substr($body, 0, $space ? $space : 200) . '...';
            }

            $data .= <<< MESSAGE
======
📩 {$message->getFrom()->getFullAddress()}
*{$message->getSubject()}*

{$body}

*{$message->getTo()[0]->getFullAddress()}*
*{$message->getDate()->format('d M Y H:i')}*
MESSAGE;

            $this->api->sendMessage(
                chatId: (string) $command->getChatId(),
                text: Str::escape($data),
                disablePreview: true,
                replyToMessageId: $command->getReplyToMessageId()
            );
        }
    }

    public function getUpdate(): iterable
    {
        $inbox = $this->connection->getMailbox('INBOX');
        return $inbox->getMessages(
            search: new Unseen(),
            sortCriteria: \SORTDATE, // https://php.net/manual/en/imap.constants.php
            descending: true
        );
    }
}
