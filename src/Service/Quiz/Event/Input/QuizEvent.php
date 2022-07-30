<?php

declare(strict_types=1);

namespace App\Service\Quiz\Event\Input;

use App\Service\InputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class QuizEvent implements InputEventInterface
{
    private array $answers;

    private string $correctAnswerId;

    private bool $multipleCorrectAnswers;

    /**
     * @see https://quizapi.io/docs/1.0/overview
     */
    public function __construct(private readonly array $update)
    {
        $this->answers = array_filter(array_values($update['answers']), fn ($v) => $v !== null);
        $this->multipleCorrectAnswers = $this->update['multiple_correct_answers'] === 'true';
        $corrects = array_filter(
            array_slice($update['correct_answers'], 0, count($this->answers)),
            fn ($v) => $v === 'true'
        );
        $key = str_replace('_correct', '', strval(array_key_first($corrects)));
        $this->correctAnswerId = strval(array_search($update['answers'][$key], $this->answers, true));
    }

    public function __toString(): string
    {
        $tags = join(',', array_map(
            fn ($t) => strtolower($t['name']),
            $this->update['tags']
        ));

        return <<< MESSAGE
Devscast QuizTime 👩‍💻 🧑‍💻 : \n
{$this->update['question']} \n
(tags: {$tags})
MESSAGE;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getCorrectAnswerId(): string
    {
        return $this->correctAnswerId;
    }

    public function isMultipleCorrectAnswers(): bool
    {
        return $this->multipleCorrectAnswers;
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-community');
    }
}
