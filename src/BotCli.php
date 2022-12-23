<?php

declare(strict_types=1);

namespace App;

use App\Command\AbstractCommand;
use App\Command\CreateProgrammingQuizCommand;
use App\Command\GetBitcoinRateCommand;
use App\Command\GetCovidUpdateCommand;
use App\Command\GetProgrammingMemeCommand;
use App\Command\ListDevscastUnreadEmailCommand;
use App\Command\ListGithubOpenIssuesCommand;
use App\Command\ListHackerNewsTopStoriesCommand;
use App\Telegram\BotTrigger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * class BotCli.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsCommand(name: 'bot:execute', description: 'execute an available bot command')]
final class BotCli extends Command
{
    private const COMMANDS_MAP = [
        'hackernews' => ListHackerNewsTopStoriesCommand::class,
        'bitcoin' => GetBitcoinRateCommand::class,
        'covid' => GetCovidUpdateCommand::class,
        'github_open_issues' => ListGithubOpenIssuesCommand::class,
        'quiz' => CreateProgrammingQuizCommand::class,
        'joieducodes' => GetProgrammingMemeCommand::class,
        'emails' => ListDevscastUnreadEmailCommand::class,
    ];

    private SymfonyStyle $io;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('cmd', InputArgument::OPTIONAL, 'the bot command to be executed');
        $this->addOption('target', 't', InputOption::VALUE_OPTIONAL, 'telegram target id');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if ($input->getArgument('cmd') !== null) {
            return;
        }

        $this->io->title('Execute Bot Command Interactive Wizard');

        $question = (new Question('command'))
            ->setAutocompleterValues(array_values(self::COMMANDS_MAP));
        $input->setArgument('cmd', $this->io->askQuestion($question));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cmd = strval($input->getArgument('cmd'));

        try {
            $stopwatch = new Stopwatch();
            $stopwatch->start('bot_command');

            $commandFqcn = self::COMMANDS_MAP[$cmd] ??
                throw new \InvalidArgumentException(sprintf('unrecognized command %s', $cmd));

            /** @var AbstractCommand $command */
            $command = new $commandFqcn(trigger: BotTrigger::CLI);
            $this->commandBus->dispatch($command);

            $event = $stopwatch->stop('bot_command');
            $this->io->comment(sprintf(
                'command executed: %s | elapsed time: %.2f ms | consumed memory: %.2f Mb',
                $cmd,
                $event->getDuration(),
                $event->getMemory() / (1024 ** 2)
            ));

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            return Command::FAILURE;
        }
    }
}
