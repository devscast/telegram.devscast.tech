<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhook", name="app_webhook_")
 * Class TelegramWebhookController
 * @package App\Controller\Webhook
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TelegramWebhookController
{
    /**
     * @Route("/telegram", name="telegram", methods={"POST"})
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(Request $request): Response
    {
        return new Response(null, Response::HTTP_OK);
    }
}
