<?php

declare(strict_types=1);

namespace App\Service\Quiz;

use App\Service\ServiceUnavailableException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * class QuizService.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class QuizService
{
    /**
     * @see https://quizapi.io/docs/1.0/endpoints
     */
    public const BASE_URL = 'https://quizapi.io/api/v1/questions';

    public function __construct(
        private readonly HttpClientInterface $client
    ) {
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
