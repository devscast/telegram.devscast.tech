<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Service\InputEventInterface;
use App\Service\OutputEventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;

final class CommandDispatcher
{
    private array $handlers = [];

    public function __construct(
        iterable $commands,
        private EventDispatcherInterface $dispatcher,
        private LoggerInterface $logger,
        private Container $container
    ) {
        foreach ($commands as $command) {
            $this->handlers[$command->handledBy()] = $command;
        }
    }

    public function dispatch(CommandInterface $command): void
    {
        try {
            $handler = $this->container->get($this->handlers[$command::class]);
            if ($handler instanceof CommandHandlerInterface) {
                $this->dispatcher->dispatch(event: $handler->handle($command));
            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
