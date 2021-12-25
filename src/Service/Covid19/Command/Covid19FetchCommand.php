<?php

declare(strict_types=1);

namespace App\Service\Covid19\Command;

use App\Service\Covid19\Covid19Service;
use App\Service\Covid19\Event\Input\Covid19Event;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'bot:covid19:fetch', description: 'Fetch Covid19 Update for DRC')]
final class Covid19FetchCommand extends Command
{
    public function __construct(
        private Covid19Service $service,
        private LoggerInterface $logger,
        private EventDispatcherInterface $dispatcher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getConfirmedCase();
            $this->dispatcher->dispatch(new Covid19Event($update));
            return Command::SUCCESS;
        } catch (ServiceUnavailableException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
