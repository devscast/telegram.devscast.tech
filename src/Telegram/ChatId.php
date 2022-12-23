<?php

declare(strict_types=1);

namespace App\Service\Telegram;

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

        if (is_int($id)) {
            $this->id = $id;
        } else {
            if (! isset($targets[$id])) {
                throw new \RuntimeException(sprintf(
                    'Invalid target choose one of : %s',
                    join(',', $ids)
                ));
            }

            $this->id = $targets[$id];
        }
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
