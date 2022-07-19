<?php

declare(strict_types=1);

namespace App\Service\Imap\Event\Input;

use App\Service\InputEventInterface;

final class ImapEvent implements InputEventInterface
{
    public function __construct(private readonly array $update)
    {
    }

    public function __toString(): string
    {
        $data = '';
        foreach ($this->update as $message) {
            $body = $message->getBodyText() ?
                $message->getBodyText() :
                ($message->getBodyHtml() ? strip_tags($message->getBodyHtml()) : '⁉️ Message Vide');

            if (mb_strlen($body) > 200) {
                $space = strripos($body, ' ', 0);
                $body = substr($body, 0, $space ? $space : 200) . '...';
            }

            $data .= <<< MESSAGE

======
📩 **{$message->getFrom()->getFullAddress()}**
**{$message->getSubject()}**

{$body}

📬 **{$message->getTo()[0]->getFullAddress()}**
📪 **{$message->getDate()->format('d M Y H:i')}**
====== 

MESSAGE;
        }

        return $data;
    }

    public function getUpdate(): array
    {
        return $this->update;
    }
}
