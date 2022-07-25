<?php

declare(strict_types=1);

namespace App\Service\Telegram;

final class TelegramTarget implements \Stringable
{
    private string $target;

    public function __construct(string $targetKey)
    {
        $targets = [
            'devscast-hq' => $_ENV['TELEGRAM_HQ_ID'],
            'devscast-team' => $_ENV['TELEGRAM_TEAM_ID'],
            'devscast-channel' => $_ENV['TELEGRAM_CHANNEL_ID'],
            'devscast-community' => $_ENV['TELEGRAM_COMMUNITY_ID'],
        ];

        if (! isset($targets[$targetKey])) {
            throw new \RuntimeException(sprintf(
                'Invalid target choose one of : %s',
                join(',', $targets)
            ));
        }

        $this->target = $targets[$targetKey];
    }

    public function __toString(): string
    {
        return $this->target;
    }
}
