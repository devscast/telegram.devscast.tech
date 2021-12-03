<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Github\GithubPayload;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class GithubWebhookController
{
    #[Route('/webhook/github', name: 'app_webhook_github', methods: ['POST'])]
    public function index(Request $request, GithubPayload $payload): Response
    {
        $payload->process($request);
        return new Response(null, Response::HTTP_OK);
    }
}
