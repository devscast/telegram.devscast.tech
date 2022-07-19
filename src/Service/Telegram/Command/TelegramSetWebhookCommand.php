<?php

declare(strict_types=1);

namespace App\Service\Telegram\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

#[AsCommand(
    name: 'bot:telegram:webhook',
    description: 'Set a webhook for telegram bot'
)]
final class TelegramSetWebhookCommand extends Command
{
    public function __construct(private readonly BotApi $api)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('url', InputArgument::OPTIONAL, 'webhook url');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        $url = $url === null ? $_ENV['TELEGRAM_WEBHOOK_URL'] : $url;

        try {
            $this->api->setWebhook($url);
            $message = sprintf('webhook %s', $url);
            $io->success($message);
        } catch (Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
