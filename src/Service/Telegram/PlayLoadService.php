<?php

declare(strict_types=1);

namespace App\Service\Telegram;

use App\Event\Telegram\CommandFiredEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use TelegramBot\Api\Types\Update;

/**
 * Class PlayLoadService
 * @package App\Service\Telegram
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PlayLoadService
{
    /**
     * PlayLoadService constructor.
     * @param EventDispatcherInterface $dispatcher
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * @param Request $request
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function negotiate(Request $request): void
    {
        $data = json_decode($request->getContent(), true);
        $update = Update::fromResponse($data);
        $message = $update->getMessage();

        if ($message) {
            if (!empty($message->getEntities())) {
                foreach ($message->getEntities() as $entity) {
                    if ($entity->getType() === 'bot_command') {
                        $command = trim(substr($message->getText(), $entity->getOffset(), $entity->getLength()));
                        $argument = trim(str_ireplace($command, "", $message->getText()));
                        $this->dispatcher->dispatch(new CommandFiredEvent($message, $command, $argument));
                    }
                }
            }
        }
    }
}
