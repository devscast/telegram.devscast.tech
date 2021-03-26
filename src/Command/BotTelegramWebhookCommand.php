<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

/**
 * Class BotSetTelegramWebhookCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotTelegramWebhookCommand extends Command
{
    protected static $defaultName = 'bot:telegram-webhook';
    private BotApi $api;

    /**
     * BotSetTelegramWebhookCommand constructor.
     * @param BotApi $api
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(BotApi $api)
    {
        parent::__construct('bot:telegram-webhook');
        $this->api = $api;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this
            ->setDescription('Set a webhook for telegram bot')
            ->addArgument('url', InputArgument::OPTIONAL, 'webhook url');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');
        $url = $url === null ? $_ENV['TELEGRAM_WEBHOOK_URL'] : $url;

        try {
            $this->api->setWebhook($url);
            $io->success("webhook {$url}");
        } catch (Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
