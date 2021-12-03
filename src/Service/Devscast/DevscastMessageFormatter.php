<?php

declare(strict_types=1);

namespace App\Service\Devscast;

final class DevscastMessageFormatter
{
    public function contactMessage(array $data): string
    {
        $name = $data['name'];
        $email = $data['email'];
        $subject = $data['subject'];
        $message = $data['message'];
        $date = date('d M Y H:i');

        return <<< MESSAGE
📨 Contact - Devscast.tech
From : {$name} <{$email}>
To : Devscast <contact@devscast.tech>
Subject : {$subject}

{$message}

🕒 {$date}
MESSAGE;
    }
}
