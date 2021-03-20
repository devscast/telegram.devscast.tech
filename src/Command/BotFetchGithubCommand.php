<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\Github\GithubIssueUpdateEvent;
use App\Service\GithubService;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BotFetchGithubCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotFetchGithubCommand extends Command
{
    protected static $defaultName = 'bot:fetch-github';
    private GithubService $service;
    private EventDispatcherInterface $dispatcher;
    private LoggerInterface $logger;

    /**
     * BotFetchGithubCommand constructor.
     * @param GithubService $service
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface $logger
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(GithubService $service, EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        parent::__construct('bot:fetch-github');
        $this->service = $service;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Interaction with Github');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getIssues();
            $this->dispatcher->dispatch(new GithubIssueUpdateEvent($update));
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
