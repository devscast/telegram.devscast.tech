<?php

declare(strict_types=1);

namespace App\Service\Imap\Command;

use App\Service\Imap\Event\Input\ImapEvent;
use App\Service\Imap\ImapService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class ImapFetchCommand.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsCommand(
    name: 'bot:imap:fetch',
    description: 'Notify the telegram channel when there is a new and unseen email'
)]
final class ImapFetchCommand extends Command
{
    public function __construct(
        private readonly ImapService $service,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $mails = $this->service->inbox();
            foreach ($mails as $mail) {
                $this->dispatcher->dispatch(new ImapEvent($mail));
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
