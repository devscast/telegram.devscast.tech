<?php

declare(strict_types=1);

namespace App\Service\Github\Command;

use App\Service\Github\Event\Input\IssueEvent;
use App\Service\Github\GithubService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class GithubFetchCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsCommand(name: 'bot:github:fetch', description: 'Interaction with Github')]
final class GithubFetchCommand extends Command
{
    protected static $defaultName = 'bot:fetch-github';

    public function __construct(
        private readonly GithubService $service,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getIssues();
            $this->dispatcher->dispatch(new IssueEvent($update));
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
