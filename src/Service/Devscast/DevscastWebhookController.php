<?php

declare(strict_types=1);

namespace App\Service\Devscast;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]final class DevscastWebhookController
{
    #[Route('/webhook/devscast', name: 'app_webhook_devscast', methods: ['POST'])]
    public function index(Request $request, DevscastPayload $payload): Response
    {
        $payload->process($request);
        return new Response(null, Response::HTTP_OK);
    }
}
