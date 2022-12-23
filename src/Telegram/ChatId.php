<?php

declare(strict_types=1);

namespace App\Telegram;

/**
 * class TelegramTarget.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class ChatId implements \Stringable
{
    private string|int $id;

    public function __construct(string|int $id)
    {
        $ids = [
            'devscast-hq' => $_ENV['TELEGRAM_HQ_ID'],
            'devscast-team' => $_ENV['TELEGRAM_TEAM_ID'],
            'devscast-channel' => $_ENV['TELEGRAM_CHANNEL_ID'],
            'devscast-community' => $_ENV['TELEGRAM_COMMUNITY_ID'],
        ];

        if (intval($id) === 0) {
            if (! isset($ids[$id])) {
                throw new \RuntimeException(sprintf(
                    'Invalid target choose one of : %s',
                    join(',', array_keys($ids))
                ));
            }

            $this->id = $ids[$id];
        } else {
            $this->id = $id;
        }
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
