<?php

declare(strict_types=1);

namespace App\Service\Quiz\Command;

use App\Service\Quiz\Event\Input\QuizEvent;
use App\Service\Quiz\QuizService;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'bot:quiz:fetch', description: 'Fetch Quiz questions')]
final class QuizFetchCommand extends Command
{
    public function __construct(
        private readonly QuizService $service,
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $dispatcher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getQuestion();
            $this->dispatcher->dispatch(new QuizEvent($update));
            return Command::SUCCESS;
        } catch (ServiceUnavailableException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
