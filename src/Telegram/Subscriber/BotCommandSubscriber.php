<?php

declare(strict_types=1);

namespace App\Telegram\Subscriber;

use App\Command\AboutCommand;
use App\Command\CreateProgrammingQuizCommand;
use App\Command\GetBitcoinRateCommand;
use App\Command\GetDevscastLatestPodcastCommand;
use App\Command\GetDevscastLatestPostCommand;
use App\Command\GetProgrammingMemeCommand;
use App\Command\ListDevscastUnreadEmailCommand;
use App\Command\ListGithubOpenIssuesCommand;
use App\Command\ListHackerNewsTopStoriesCommand;
use App\Command\RulesCommand;
use App\Command\SocialsLinksCommand;
use App\Command\StartCommand;
use App\Telegram\BotCommandEvent;
use App\Telegram\Exception\RestrictedCommandException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;

/**
 * class BotCommandSubscriber.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
final class BotCommandSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BotApi $api,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $commandBus
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BotCommandEvent::class => 'onBotCommand',
        ];
    }

    public function onBotCommand(BotCommandEvent $event): void
    {
        $message = $event->getMessage();
        $command = str_replace('@DevscastNotifierBot', '', $event->getCommand());

        try {
            match ($command) {
                '/start' => $this->dispatchSync(new StartCommand(message: $message)),
                '/about' => $this->dispatchSync(new AboutCommand(message: $message)),
                '/socials' => $this->dispatchSync(new SocialsLinksCommand(message: $message)),
                '/rules' => $this->dispatchSync(new RulesCommand(message: $message)),
                '/posts' => $this->dispatchSync(new GetDevscastLatestPostCommand(message: $message)),
                '/podcasts' => $this->dispatchSync(new GetDevscastLatestPodcastCommand(message: $message)),
                '/hackernews' => $this->dispatchSync(new ListHackerNewsTopStoriesCommand(message: $message)),
                '/joieducodes' => $this->dispatchSync(new GetProgrammingMemeCommand(message: $message)),
                '/bitcoin' => $this->dispatchSync(new GetBitcoinRateCommand(message: $message)),
                '/quiz' => $this->dispatchSync(new CreateProgrammingQuizCommand(message: $message)),
                '/emails' => $this->dispatchSync(new ListDevscastUnreadEmailCommand(message: $message)),
                '/issues' => $this->commandBus->dispatch(new ListGithubOpenIssuesCommand(message: $message)),
                default => $this->sendCommandNotFound(
                    chatId: (int)$message->getChat()->getId(),
                    messageId: (int)$message->getMessageId()
                )
            };
        } catch (RestrictedCommandException $e) {
            $this->api->sendMessage(
                chatId: $message->getChat()->getId(),
                text: $e->getMessage(),
                replyToMessageId: (int)$message->getMessageId()
            );
        } catch (\Throwable $e) {
            $this->api->sendMessage(
                chatId: $message->getChat()->getId(),
                text: "💀 sorry i'm down !",
                replyToMessageId: (int)$message->getMessageId()
            );
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @throws \Throwable
     */
    private function sendCommandNotFound(int|string $chatId, int $messageId): void
    {
        $this->api->sendMessage(
            chatId: $chatId,
            text: '💀 Error 404 !',
            replyToMessageId: $messageId
        );
    }

    /**
     * @throws \Throwable
     */
    private function dispatchSync(object $command): ?Envelope
    {
        try {
            return $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                $e = $e->getPrevious();
            }
            if (null !== $e) {
                throw $e;
            }

            return null;
        }
    }
}
