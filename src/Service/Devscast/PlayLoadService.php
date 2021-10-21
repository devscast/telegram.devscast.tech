<?php

declare(strict_types=1);

namespace App\Service\Devscast;

use App\Event\Devscast\ContactFormSubmittedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PlayLoadService
 * @package App\Service\Github
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PlayLoadService
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Trigger the right event with the right data
     * @param Request $request
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function negotiate(Request $request): void
    {
        $event = $request->headers->get('X-Devscast-Event');
        $data = json_decode($request->getContent(), true);

        switch ($event) {
            case 'contact_form_submitted':
                $this->dispatcher->dispatch(new ContactFormSubmittedEvent(
                    name: $data['name'],
                    email: $data['email'],
                    subject: $data['subject'],
                    message: $data['message']
                ));
                break;
        }
    }
}
