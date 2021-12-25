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

#[AsCommand(name: 'bot:github:fetch', description: 'Interaction with Github')]
final class GithubFetchCommand extends Command
{
    public function __construct(
        private GithubService            $service,
        private EventDispatcherInterface $dispatcher,
        private LoggerInterface          $logger
    )
    {
        parent::__construct('bot:fetch-github');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getIssues();
            $this->dispatcher->dispatch(new IssueEvent($update));
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
