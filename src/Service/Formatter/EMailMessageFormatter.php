<?php

declare(strict_types=1);

namespace App\Service\Formatter;

/**
 * Class EMailMessageFormatter
 * @package App\Service\Formatter
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EMailMessageFormatter
{
    /**
     * @param iterable $messages
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function format(iterable $messages): array
    {
        $data = [];
        foreach ($messages as $message) {
            $body = $message->getBodyText() ?
                $message->getBodyText() :
                ($message->getBodyHtml() ? strip_tags($message->getBodyHtml()) : '⁉️ Message Vide');

            if (mb_strlen($body) > 200) {
                $space = strripos($body, " ", 0);
                $body = substr($body, 0, $space ? $space : 200) . '...';
            }

            $data[] = <<< MESSAGE
📩 **{$message->getFrom()->getFullAddress()}**
**{$message->getSubject()}**

{$body}

📬 **{$message->getTo()[0]->getFullAddress()}**
📪 **{$message->getDate()->format('d M Y H:i')}**
🕒 Prochain rappel dans 1h
MESSAGE;
        }

        return $data;
    }
}
