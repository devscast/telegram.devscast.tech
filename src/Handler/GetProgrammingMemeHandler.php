<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\GetProgrammingMemeCommand;
use App\Telegram\Exception\ServiceUnavailableException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class LulzHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class GetProgrammingMemeHandler
{
    private const BASE_URL = 'https://lesjoiesducode.fr/random';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ServiceUnavailableException
     */
    public function __invoke(GetProgrammingMemeCommand $command): void
    {
        $update = $this->getUpdate();
        $this->api->sendDocument(
            chatId: (string) $command->getChatId(),
            document: new \CURLFile($update['image_url'], mime_type: 'image/gif'),
            caption: (string) $update['title'],
            replyToMessageId: $command->getReplyToMessageId()
        );
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getUpdate(): array
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
