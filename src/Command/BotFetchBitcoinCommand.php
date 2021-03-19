<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\Covid19UpdateEvent;
use App\Service\BitcoinService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BotFetchBitcoinCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotFetchBitcoinCommand extends Command
{
    protected static $defaultName = 'bot:fetch-bitcoin';
    private BitcoinService $service;
    private EventDispatcherInterface $dispatcher;
    private LoggerInterface $logger;

    /**
     * BotFetchCovid19Command constructor.
     * @param BitcoinService $service
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $dispatcher
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(BitcoinService $service, LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->service = $service;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        parent::__construct('bot:fetch-bitcoin');
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Get current bitcoin rate');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $update = $this->service->getRate();
            $this->dispatcher->dispatch(new Covid19UpdateEvent($update));


            $io->success('Posted');
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e, $e->getTrace());
            return Command::FAILURE;
        }
    }
}
