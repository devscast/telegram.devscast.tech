<?php

declare(strict_types=1);

namespace App\Service\Devscast;

use App\Service\Devscast\Event\DevscastContactFormSubmittedEvent;
use App\Service\PayloadInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

final class DevscastPayload implements PayloadInterface
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    public function process(Request $request): void
    {
        /** @var array $data */
        $data = json_decode(json: (string)$request->getContent(), associative: true);
        $event = $request->headers->get('X-Devscast-Event');

        $event = match ($event) {
            'contact_form_submitted' => new DevscastContactFormSubmittedEvent(
                name: $data['name'],
                email: $data['email'],
                subject: $data['subject'],
                message: $data['message']
            ),
            default => null
        };

        if ($event !== null) {
            $this->dispatcher->dispatch($event);
        }
    }
}
