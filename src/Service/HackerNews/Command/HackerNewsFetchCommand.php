<?php

declare(strict_types=1);

namespace App\Service\HackerNews\Command;

use App\Service\HackerNews\Event\Input\HackerNewsEvent;
use App\Service\HackerNews\HackerNewsService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class HackerNewsFetchCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsCommand(name: 'bot:hackernews:fetch', description: 'Fetch Hacker News top stories')]
final class HackerNewsFetchCommand extends Command
{
    public function __construct(
        private readonly HackerNewsService $service,
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $dispatcher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getTopStories();
            $this->dispatcher->dispatch(new HackerNewsEvent($update));
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
