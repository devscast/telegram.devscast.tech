<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Telegram\TelegramPayload;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class TelegramWebhookController
{
    #[Route('/webhook/telegram', name: 'app_webhook_telegram', methods: ['POST'])]
    public function index(Request $request, TelegramPayload $payload): Response
    {
        $payload->process($request);
        return new Response(null, Response::HTTP_OK);
    }
}
