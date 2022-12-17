<?php

declare(strict_types=1);

namespace App\Service\Devscast;

use Laminas\Feed\Reader\Entry\EntryInterface;
use Laminas\Feed\Reader\Reader;

/**
 * class FeedReaderService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class FeedReaderService
{
    public function getData(string $type): array
    {
        $feed = Reader::import(match ($type) {
            'podcasts' => $_ENV['DEVSCAST_PODCASTS_RSS_URL'],
            'posts' => $_ENV['DEVSCAST_POSTS_RSS_URL'],
            default => throw new \InvalidArgumentException(
                sprintf('Unknown %s type only (podcasts, posts) are supported', $type)
            )
        });

        $data = [
            'description' => $feed->getDescription(),
            'title' => $feed->getTitle(),
            'last_update' => $feed->getDateModified() ? $feed->getDateModified()->format('d M Y') : date('d M Y'),
            'items' => [],
        ];

        $count = 0;
        /** @var EntryInterface $item */
        foreach ($feed as $item) {
            if ($count <= 5) {
                $data['item'] = [
                    'text' => $item->getTitle(),
                    'url' => $item->getLink(),
                ];
            }
            $count++;
        }

        return $data;
    }
}
