<?php

declare(strict_types=1);

namespace App\Telegram;

/**
 * Class Topic.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class Topic
{
    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function general(): self
    {
        return new self(1);
    }

    public static function contents(): self
    {
        return new self(5107);
    }

    public static function quiz(): self
    {
        return new self(7927);
    }

    public static function autopromotions(): self
    {
        return new self(5109);
    }

    public static function projects(): self
    {
        return new self(5102);
    }

    public static function help(): self
    {
        return new self(5111);
    }

    public static function opportunities(): self
    {
        return new self(5549);
    }

    public static function resources(): self
    {
        return new self(5183);
    }

    public static function presentations(): self
    {
        return new self(5105);
    }

    public static function rules(): self
    {
        return new self(5120);
    }

    public static function humor(): self
    {
        return new self(7932);
    }

    public static function announcements(): self
    {
        return new self(7937);
    }

    public function toInt(): int
    {
        return $this->id;
    }
}
