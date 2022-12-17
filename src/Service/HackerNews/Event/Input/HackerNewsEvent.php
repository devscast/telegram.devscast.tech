<?php

declare(strict_types=1);

namespace App\Service\HackerNews\Event\Input;

use App\Service\InputEventInterface;
use App\Service\Telegram\TelegramTarget;

/**
 * class HackerNewsEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class HackerNewsEvent implements InputEventInterface
{
    public function __construct(private readonly array $update)
    {
    }

    public function __toString(): string
    {
        $update = '';

        foreach ($this->update as $news) {
            $update .= <<< MESSAGE
({$news['type']}) {$news['title']} : {$news['url']}  \n\n
MESSAGE;
        }

        return <<< MESSAGE
Devscast NewsLinks 👩‍💻 🧑‍💻 : \n
{$update}
MESSAGE;
    }

    public function getUpdate(): string|array
    {
        return $this->update;
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-community');
    }
}
