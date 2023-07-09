<?php

declare(strict_types=1);

namespace App\Command;

use App\Telegram\BotReply;
use App\Telegram\BotTrigger;
use App\Telegram\ChatId;
use App\Telegram\Exception\RestrictedCommandException;
use TelegramBot\Api\Types\Message;

/**
 * class AbstractCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
abstract class AbstractCommand
{
    public const NAME = 'start';

    public const RESTRICTED = false;

    public const TRIGGERS = [BotTrigger::CHAT];

    public const REPLY_MODE = BotReply::CURRENT_CHAT;

    public const CLI_DEVSCAST_CHAT = 'devscast-community';

    public function __construct(
        public readonly BotTrigger $trigger = BotTrigger::CHAT,
        public readonly ?Message $message = null,
    ) {
        if (!in_array($this->trigger, static::TRIGGERS, true)) {
            throw new \RuntimeException(sprintf(
                'Invalid trigger for command %s, choose one of : %s',
                static::NAME,
                join(',', static::TRIGGERS)
            ));
        }

        if ($this->trigger === BotTrigger::CHAT && $this->message === null) {
            throw new \RuntimeException('Message is required for chat trigger');
        }

        if ($this->trigger === BotTrigger::CHAT && static::RESTRICTED) {
            $from = $this->message?->getFrom()?->getId();
            if (!in_array((string)$from, explode(',', $_ENV['DEVSCAST_WHITELISTED_IDS']), true)) {
                throw new RestrictedCommandException();
            }
        }
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function getPrivateMessageCommandName(): string
    {
        return sprintf('/%s', static::NAME);
    }

    public function getGroupeMessageCommandName(): string
    {
        return sprintf('/%s@DevscastNotifierBot', static::NAME);
    }

    public function getChatId(): ChatId
    {
        if ($this->trigger === BotTrigger::CHAT && $this->message !== null) {
            if (static::REPLY_MODE === BotReply::CURRENT_CHAT) {
                return new ChatId((int) $this->message->getChat()->getId());
            }
            return new ChatId((int) $this->message->getFrom()?->getId());
        }

        return new ChatId(static::CLI_DEVSCAST_CHAT);
    }

    public function getReplyToMessageId(): ?int
    {
        if ($this->trigger === BotTrigger::CLI || $this->trigger === BotTrigger::WEBHOOK) {
            return null;
        }

        /** @var int|null $id */
        $id = $this->message?->getMessageId();
        return $id;
    }
}
