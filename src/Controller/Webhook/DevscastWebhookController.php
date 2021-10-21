<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use App\Service\Devscast\PlayLoadService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GithubWebhookController
 * @package App\Controller\Webhook
 * @author bernard-ng <bernard@devscast.tech>
 */
class DevscastWebhookController
{
    #[Route('/webhook/devscast', name: 'app_webhook_devscast', methods: ['POST'])]
    public function index(Request $request, PlayLoadService $service): Response
    {
        $service->negotiate($request);
        return new Response(null, Response::HTTP_OK);
    }
}
