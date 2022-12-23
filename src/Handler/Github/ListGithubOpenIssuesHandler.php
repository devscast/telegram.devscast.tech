<?php

declare(strict_types=1);

namespace App\Handler\Github;

use App\Command\ListGithubOpenIssuesCommand;
use App\Telegram\Str;
use Github\Api\Issue;
use Github\Client;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class GithubService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class ListGithubOpenIssuesHandler
{
    public function __construct(
        private readonly Client $client,
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(ListGithubOpenIssuesCommand $command): void
    {
        $update = $this->getUpdate();
        $data = '';
        foreach ($update as $issue) {
            $title = sprintf('*#%s*', $issue['number']);
            $assignee = $issue['assignee'] ? $issue['assignee']['login'] : 'bernard-ng';
            $data .= "🛠 *{$title}* -> **{$assignee}* \n";
        }

        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: Str::escape("*issues sur devscast.tech* \n\n{$data}\n\n"),
            parseMode: 'MarkdownV2',
            replyToMessageId: $command->getReplyToMessageId()
        );
    }

    public function getUpdate(string $username = 'devscast', string $repository = 'devscast.tech'): array
    {
        /** @var Issue $api */
        $api = $this->client->api('issue');
        return $api->all($username, $repository, params: [
            'state' => 'open',
        ]);
    }
}
