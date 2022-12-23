<?php

declare(strict_types=1);

namespace App\Webhook\Devscast;

use App\Telegram\ChatId;
use App\Webhook\WebhookEventInterface;

/**
 * class ContentCreatedEvent.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ContentCreatedEvent implements WebhookEventInterface
{
    private function __construct(
        public string $name,
        public string $short_description,
        public string $status,
        public string $visibility,
        public string $author,
        public string $link,
        public string $type
    ) {
    }

    public function __toString(): string
    {
        return <<< MESSAGE
Nouveau Contenu<{$this->type}> -> Devscast.tech

{$this->name}
======

{$this->short_description}

status : {$this->status}
visibility : {$this->visibility}
auteur : {$this->author}

======

{$this->link}
MESSAGE;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            short_description: $data['short_description'],
            status: $data['status'],
            visibility: $data['visibility'],
            author: $data['author'],
            link: $data['link'],
            type: $data['type']
        );
    }

    public function getChatId(): ChatId
    {
        return new ChatId('devscast-team');
    }

    public function getUpdate(): string|iterable
    {
        return $this->__toString();
    }
}
