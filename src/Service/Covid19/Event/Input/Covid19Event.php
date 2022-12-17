<?php

declare(strict_types=1);

namespace App\Service\Covid19\Event\Input;

use App\Service\InputEventInterface;
use App\Service\Telegram\TelegramTarget;

/**
 * class Covid19Event.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class Covid19Event implements InputEventInterface
{
    public function __construct(private readonly array $update)
    {
    }

    public function __toString(): string
    {
        $date = date('d M Y H:i');
        return <<< MESSAGE
Voici les dernières actualités sur le covid19 en RDC \n
🤒 Cas Confirmés : {$this->update['confirmed']}
✨ Guérisons : {$this->update['recovered']}
😓 Morts : {$this->update['deaths']}

{$date}
MESSAGE;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }
}
