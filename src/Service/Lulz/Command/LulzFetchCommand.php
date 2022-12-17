<?php

declare(strict_types=1);

namespace App\Service\Lulz\Command;

use App\Service\Lulz\Event\Input\LulzEvent;
use App\Service\Lulz\LulzService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class LulzFetchCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsCommand(name: 'bot:lulz:fetch', description: 'Fetch a joke from lesjoiesducode.fr')]
final class LulzFetchCommand extends Command
{
    public function __construct(
        private readonly LulzService $service,
        private readonly LoggerInterface $logger,
        private readonly EventDispatcherInterface $dispatcher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $update = $this->service->getMeme();
            $this->dispatcher->dispatch(new LulzEvent($update));
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
