<?php

declare(strict_types=1);

namespace App\Service\Bitcoin\Command;

use App\Service\Bitcoin\BitcoinService;
use App\Service\Bitcoin\Event\Input\BitcoinEvent;
use App\Service\ServiceUnavailableException;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'bot:bitcoin:fetch', description: 'Get current bitcoin rate')]
final class BitcoinFetchCommand extends Command
{
    public function __construct(
        private readonly BitcoinService $service,
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $dispatcher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getRate();
            $this->dispatcher->dispatch(new BitcoinEvent($update));
            return Command::SUCCESS;
        } catch (Exception | ServiceUnavailableException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
