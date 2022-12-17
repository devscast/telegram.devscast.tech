<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Devscast\Event\Output\ContactSubmittedEvent;
use App\Service\Devscast\Event\Output\ContentCreatedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * class DevscastWebhookController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final class DevscastWebhookController extends AbstractController
{
    #[Route('/webhook/devscast', name: 'app_webhook_devscast', methods: ['POST'])]
    public function index(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        /** @var array $data */
        $data = json_decode(json: (string) $request->getContent(), associative: true);
        $event = (string) $request->headers->get('X-Devscast-Event', '');

        $event = match ($event) {
            'contact_form_submitted' => ContactSubmittedEvent::fromArray($data),
            'content_created' => ContentCreatedEvent::fromArray($data),
            default => null
        };

        if ($event !== null) {
            $dispatcher->dispatch($event);
        }

        return new Response(null, Response::HTTP_OK);
    }
}
