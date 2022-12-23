<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\HackerNewsStoriesCommand;
use App\Event\HackerNewsStoriesEvent;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class HackerNewsService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class HackerNewsStoriesHandler
{
    private const BASE_URL = 'https://hacker-news.firebaseio.com/v0/';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function __invoke(HackerNewsStoriesCommand $command): void
    {
        $update = $this->getTopStories();
        $this->dispatcher->dispatch(new HackerNewsStoriesEvent($update));
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getTopStories(): array
    {
        try {
            $stories = [];
            $storiesIds = array_slice(
                array: $this->client->request('GET', self::BASE_URL . '/newstories.json')->toArray(),
                offset: 0,
                length: 5
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
