<?php

declare(strict_types=1);

namespace App\Service\Lulz;

use App\Service\ServiceUnavailableException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class LulzService
{
    private const BASE_URL = 'https://lesjoiesducode.fr/random';

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getMeme(): array
    {
        try {
            $crawler = new Crawler($this->client->request('GET', self::BASE_URL, [
                'max_redirects' => 2,
            ])->getContent());

            if ($crawler->filter("[type='image/gif']")->count() === 1) {
                $title = $crawler->filter('h1')->first()->text();
                $url = $crawler->filter("[type='image/gif']")->first()->attr('data');

                return [
                    'title' => $title,
                    'image_url' => $url,
                ];
            }

            throw new ServiceUnavailableException('lesjoiesducode.fr is down !');
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
