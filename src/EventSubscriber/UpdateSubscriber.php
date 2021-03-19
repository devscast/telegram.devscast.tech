<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\Covid19UpdateEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

/**
 * Class UpdateSubscriber
 * @package App\EventSubscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class UpdateSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ChatterInterface $notifier;

    /**
     * UpdateSubscriber constructor.
     * @param ChatterInterface $notifier
     * @param LoggerInterface $logger
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(ChatterInterface $notifier, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->notifier = $notifier;
    }

    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            Covid19UpdateEvent::class => 'onCovid19Update'
        ];
    }

    /**
     * @param Covid19UpdateEvent $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onCovid19Update(Covid19UpdateEvent $event): void
    {
        try {
            $message = new ChatMessage($event->getUpdate());
            $this->notifier->send($message);
        } catch (\Exception | TransportExceptionInterface $e) {
            $this->logger->error($e);
        }
    }
}
