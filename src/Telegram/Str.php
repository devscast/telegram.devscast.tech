<?php

declare(strict_types=1);

namespace App\Telegram;

final class Str
{
    public static function escape(string $subject): string
    {
        return str_replace(
            search: ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'],
            replace: ['\\_', '\\*', '\\[', '\\]', '\\(', '\\)', '\\~', '\\`', '\\>', '\\#', '\\+', '\\-', '\\=', '\\|', '\\{', '\\}', '\\.', '\\!'],
            subject: $subject
        );
    }
}
