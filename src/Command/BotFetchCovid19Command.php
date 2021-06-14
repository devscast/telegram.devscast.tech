<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\Covid19UpdateEvent;
use App\Service\Covid19Service;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class BotFetchCovid19Command
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotFetchCovid19Command extends Command
{
    protected static $defaultName = 'bot:fetch-covid19';
    private Covid19Service $service;
    private EventDispatcherInterface $dispatcher;
    private LoggerInterface $logger;

    /**
     * BotFetchCovid19Command constructor.
     * @param Covid19Service $service
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $dispatcher
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(Covid19Service $service, LoggerInterface $logger, EventDispatcherInterface $dispatcher)
    {
        $this->service = $service;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
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
        } catch (Exception |
        ClientExceptionInterface |
        DecodingExceptionInterface |
        RedirectionExceptionInterface |
        ServerExceptionInterface |
        TransportExceptionInterface $e
        ) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
