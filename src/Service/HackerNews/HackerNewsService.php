<?php

declare(strict_types=1);

namespace App\Service\HackerNews;

use App\Service\ServiceUnavailableException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HackerNewsService
{
    private const BASE_URL = 'https://hacker-news.firebaseio.com/v0/';

    public function __construct(private readonly HttpClientInterface $client)
    {
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
