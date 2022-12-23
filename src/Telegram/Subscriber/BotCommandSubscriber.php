<?php

declare(strict_types=1);

namespace App\Telegram;

use App\Command\AboutCommand;
use App\Command\CreateProgrammingQuizCommand;
use App\Command\GetBitcoinRateCommand;
use App\Command\GetCovidUpdateCommand;
use App\Command\GetDevscastLatestPodcastCommand;
use App\Command\GetDevscastLatestPostCommand;
use App\Command\GetProgrammingMemeCommand;
use App\Command\ListDevscastUnreadEmailCommand;
use App\Command\ListHackerNewsTopStoriesCommand;
use App\Command\RulesCommand;
use App\Command\SocialsLinksCommand;
use App\Command\StartCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
                '/start' => $this->commandBus->dispatch(new StartCommand(message: $message)),
                '/about' => $this->commandBus->dispatch(new AboutCommand(message: $message)),
                '/socials' => $this->commandBus->dispatch(new SocialsLinksCommand(message: $message)),
                '/rules' => $this->commandBus->dispatch(new RulesCommand(message: $message)),
                '/posts' => $this->commandBus->dispatch(new GetDevscastLatestPostCommand(message: $message)),
                '/podcasts' => $this->commandBus->dispatch(new GetDevscastLatestPodcastCommand(message: $message)),
                '/hackernews' => $this->commandBus->dispatch(new ListHackerNewsTopStoriesCommand(message: $message)),
                '/joieducodes' => $this->commandBus->dispatch(new GetProgrammingMemeCommand(message: $message)),
                '/bitcoin' => $this->commandBus->dispatch(new GetBitcoinRateCommand(message: $message)),
                '/covid' => $this->commandBus->dispatch(new GetCovidUpdateCommand(message: $message)),
                '/quiz' => $this->commandBus->dispatch(new CreateProgrammingQuizCommand(message: $message)),
                '/emails' => $this->commandBus->dispatch(new ListDevscastUnreadEmailCommand(message: $message)),
                default => $this->sendCommandNotFound($message->getChat()->getId(), $message->getMessageId())
            };
        } catch (RestrictedCommandException $e) {
            $this->api->sendMessage(
                chatId: $message->getChat()->getId(),
                text: $e->getMessage(),
                replyToMessageId: $message->getMessageId()
            );
        } catch (HandlerFailedException $e) {
            $this->api->sendMessage(
                chatId: $message->getChat()->getId(),
                text: "💀 sorry i'm down !",
                replyToMessageId: $message->getMessageId()
            );
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function sendCommandNotFound(int|string $chatId, int $messageId): void
    {
        $this->api->sendMessage(
            chatId: $chatId,
            text: '💀 Error 404 !',
            replyToMessageId: $messageId
        );
    }
}
