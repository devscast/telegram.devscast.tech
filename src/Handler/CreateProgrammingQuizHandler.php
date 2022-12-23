<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\QuizCommand;
use App\Event\QuizEvent;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class QuizHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class QuizHandler
{
    /**
     * @see https://quizapi.io/docs/1.0/endpoints
     */
    public const BASE_URL = 'https://quizapi.io/api/v1/questions';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EventDispatcherInterface $dispatcher
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function __invoke(QuizCommand $command): void
    {
        $update = $this->getQuestion();
        $this->dispatcher->dispatch(new QuizEvent($update));
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getQuestion(): array
    {
        try {
            return $this->client->request('GET', self::BASE_URL, [
                'query' => [
                    'apiKey' => $_ENV['QUIZAPI_KEY'],
                    'limit' => 1,
                    'difficulty' => 'easy',
                ],
            ])->toArray()[0];
        } catch (\Throwable $e) {
            throw ServiceUnavailableException::fromException($e);
        }
    }
}
