<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\Covid19UpdateEvent;
use App\Service\Covid19Service;
use App\Service\ServiceUnavailableException;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BotFetchCovid19Command
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotFetchCovid19Command extends Command
{
    protected static $defaultName = 'bot:fetch-covid19';

    public function __construct(
        private Covid19Service $service,
        private LoggerInterface $logger,
        private EventDispatcherInterface $dispatcher
    ) {
        parent::__construct('bot:fetch-covid19');
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Fetch Covid19 Update for DRC');
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
            $update = $this->service->getConfirmedCase();
            $this->dispatcher->dispatch(new Covid19UpdateEvent($update));
            return Command::SUCCESS;
        } catch (Exception | ServiceUnavailableException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
