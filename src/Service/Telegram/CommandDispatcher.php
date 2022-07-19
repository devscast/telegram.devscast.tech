<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;

final class CommandDispatcher
{
    private array $handlers = [];

    public function __construct(
        iterable $commands,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly LoggerInterface $logger,
        private readonly Container $container
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
