<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\BitcoinUpdateEvent;
use App\Event\Covid19UpdateEvent;
use App\Event\Telegram\CommandFiredEvent;
use App\Service\BitcoinService;
use App\Service\Covid19Service;
use App\Service\ServiceUnavailableException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TelegramWebhookSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TelegramWebhookSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Covid19Service $covid19Service,
        private BitcoinService $bitcoinService,
        private EventDispatcherInterface $dispatcher,
        private LoggerInterface $logger,
    ) {
    }


    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            CommandFiredEvent::class => 'onCommandFired'
        ];
    }

    /**
     * @param CommandFiredEvent $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onCommandFired(CommandFiredEvent $event): void
    {
        try {
            switch ($event->getCommand()) {
                case '/covid19@DevscastNotifierBot':
                case '/covid19':
                    $update = $this->covid19Service->getConfirmedCase();
                    $this->dispatcher->dispatch(new Covid19UpdateEvent($update));
                    break;

                case '/bitcoin@DevscastNotifierBot':
                case '/bitcoin':
                    $update = $this->bitcoinService->getRate();
                    $this->dispatcher->dispatch(new BitcoinUpdateEvent($update));
                    break;
            }
        } catch (ServiceUnavailableException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
