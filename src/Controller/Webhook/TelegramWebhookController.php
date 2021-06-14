<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TelegramWebhookController
 * @package App\Controller\Webhook
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TelegramWebhookController
{
    #[Route('/webhook/telegram', name: 'app_webhook_telegram', methods: ['POST'])]
    public function index(Request $request): Response
    {
        return new Response(null, Response::HTTP_OK);
    }
}
