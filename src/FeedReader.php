<?php

declare(strict_types=1);

namespace App;

use App\Telegram\Exception\ServiceUnavailableException;
use Laminas\Feed\Reader\Entry\EntryInterface;
use Laminas\Feed\Reader\Reader;

/**
 * class FeedReader.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
trait FeedReader
{
    /**
     * @throws ServiceUnavailableException
     */
    public function getData(string $type): array
    {
        try {
            $feed = Reader::import(match ($type) {
                'podcasts' => $_ENV['DEVSCAST_PODCASTS_RSS_URL'],
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
                if ($count < 5) {
                    $data['items'][] = [[
                        'text' => $item->getTitle(),
                        'url' => $item->getLink(),
                    ]];
                }
                $count++;
            }

            return $data;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
