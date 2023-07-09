<?php

declare(strict_types=1);

namespace App\Telegram;

/**
 * Class Str.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
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
