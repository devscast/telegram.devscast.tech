<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\EMailUpdateEvent;
use App\Service\Mailer\EMailService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BotFetchMailCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotFetchEMailCommand extends Command
{
    protected static $defaultName = 'bot:fetch-email';
    private EMailService $service;
    private EventDispatcherInterface $dispatcher;

    /**
     * BotFetchMailCommand constructor.
     * @param EMailService $service
     * @param EventDispatcherInterface $dispatcher
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(EMailService $service, EventDispatcherInterface $dispatcher)
    {
        $this->service = $service;
        $this->dispatcher = $dispatcher;
        parent::__construct('bot:fetch-email');
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Notify the telegram channel when there is a new and unseen email');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mails = $this->service->inbox();
        if (count($mails) > 0) {
            foreach ($mails as $mail) {
                $this->dispatcher->dispatch(new EMailUpdateEvent($mail));
            }
        }
        return Command::SUCCESS;
    }
}
