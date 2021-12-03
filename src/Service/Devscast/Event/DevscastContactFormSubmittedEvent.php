<?php

declare(strict_types=1);

namespace App\Service\Devscast\Event;

final class DevscastContactFormSubmittedEvent
{
    public function __construct(
        private string $name,
        private string $email,
        private string $subject,
        private string $message
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
