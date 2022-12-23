<?php

declare(strict_types=1);

namespace App\Handler;

use App\Command\CreateProgrammingQuizCommand;
use App\Telegram\Exception\ServiceUnavailableException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TelegramBot\Api\BotApi;

/**
 * class QuizHandler.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsMessageHandler]
final class CreateProgrammingQuizHandler
{
    /**
     * @see https://quizapi.io/docs/1.0/endpoints
     */
    public const BASE_URL = 'https://quizapi.io/api/v1/questions';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly BotApi $api
    ) {
    }

    /**
     * @throws ServiceUnavailableException
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\HttpException
     * @throws \TelegramBot\Api\InvalidArgumentException
     * @throws \TelegramBot\Api\InvalidJsonException
     */
    public function __invoke(CreateProgrammingQuizCommand $command): void
    {
        $update = $this->getUpdate();
        if ($update['multiple_correct_answers'] === 'true') {
            return;
        }

        $answers = array_filter(array_values($update['answers']), fn ($v) => $v !== null);
        $corrects = array_filter(
            array_slice($update['correct_answers'], 0, count($answers)),
            fn ($v) => $v === 'true'
        );
        $key = str_replace('_correct', '', strval(array_key_first($corrects)));
        $correctAnswerId = strval(array_search($update['answers'][$key], $answers, true));
        $tags = join(',', array_map(fn ($t) => strtolower($t['name']), $update['tags']));

        // send the question to the user
        $message = $this->api->sendMessage(
            chatId: (string) $command->getChatId(),
            text: "{$update['question']} (tags: {$tags})",
        );

        // send the answers to the user
        $this->api->sendPoll(
            chatId: (string) $command->getChatId(),
            question: 'Votre choix ?',
            options: $answers,
            isAnonymous: true,
            type: 'quiz',
            correctOptionId: $correctAnswerId,
            replyToMessageId: $message->getMessageId(),
        );
    }

    /**
     * @throws ServiceUnavailableException
     */
    public function getUpdate(): array
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
