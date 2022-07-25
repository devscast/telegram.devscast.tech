<?php

declare(strict_types=1);

namespace App\Service\Github\Event\Output;

use App\Service\OutputEventInterface;
use App\Service\Telegram\TelegramTarget;

final class PushEvent implements OutputEventInterface
{
    public function __construct(
        protected array $data
    ) {
    }

    public function __toString(): string
    {
        $commit = substr(strval($this->data['after']), 0, 8);
        $project = $this->data['repository']['name'];
        $pusher = $this->data['pusher']['name'];
        $ref = $this->data['ref'];
        $message = $this->data['head_commit']['message'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
⬆️ Push : {$project}
📍 référence : {$ref}
🗒 {$commit} : {$message}

👨‍💻 {$pusher}
🕒 {$date}
MESSAGE;
    }

    public function getTarget(): TelegramTarget
    {
        return new TelegramTarget('devscast-team');
    }
}
