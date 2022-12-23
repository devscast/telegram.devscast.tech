<?php

declare(strict_types=1);

namespace App\Webhook\Devscast;

use App\Telegram\ChatId;
use App\Webhook\WebhookEventInterface;

/**
 * class ContactSubmittedEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ContactSubmittedEvent implements WebhookEventInterface
{
    private function __construct(
        public string $name,
        public string $email,
        public string $subject,
        public string $message
    ) {
    }

    public function __toString(): string
    {
        $date = date('d M Y H:i');

        return <<< MESSAGE
📨 Contact - Devscast.tech
From : {$this->name} <{$this->email}>
To : Devscast <contact@devscast.tech>
Subject : {$this->subject}

{$this->message}

🕒 {$date}
MESSAGE;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            subject: $data['subject'],
            message: $data['message']
        );
    }

    public function getChatId(): ChatId
    {
        return new ChatId('devscast-hq');
    }

    public function getUpdate(): string|iterable
    {
        return $this->__toString();
    }
}
