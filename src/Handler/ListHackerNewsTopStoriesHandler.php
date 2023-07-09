<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\ListHackerNewsTopStoriesCommand;
use App\Telegram\Exception\ServiceUnavailableException;
use App\Telegram\Str;
use App\Telegram\Topic;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class HackerNewsService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class ListHackerNewsTopStoriesHandler
{
    private const BASE_URL = 'https://hacker-news.firebaseio.com/v0/';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __invoke(ListHackerNewsTopStoriesCommand $command): void
    {
        $update = $this->getUpdate();
        $stories = '';

        foreach ($update as $news) {
            $title = Str::escape($news['title']);
            $stories .= "[{$title}]({$news['url']}) \n\n";
        }

        $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: "*10 stories intéressants* \n\n{$stories}\n\n _source: hackernews_",
            parseMode: 'MarkdownV2',
            disablePreview: true,
            replyToMessageId: $command->getReplyToMessageId(),
            messageThreadId: Topic::resources()->toInt()
        );
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getUpdate(): array
    {
        try {
            $stories = [];
            $storiesIds = array_slice(
                array: $this->client->request('GET', self::BASE_URL . '/newstories.json')->toArray(),
                offset: 0,
                length: 11
            );

            foreach ($storiesIds as $storyId) {
                $stories[] = $this->client->request('GET', self::BASE_URL . "/item/{$storyId}.json")->toArray();
            }

            return array_filter($stories, fn ($s) => isset($s['url']));
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
