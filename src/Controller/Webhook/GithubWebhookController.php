<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use App\Service\Github\PlayLoadService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GithubWebhookController
 * @package App\Controller\Webhook
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubWebhookController
{
    /**
     * @Route("", name="app_main")
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function main(Request $request): Response
    {
        return new Response(null, Response::HTTP_OK);
    }

    /**
     * @Route("/webhook/github", name="app_webhook_github", methods={"POST"})
     * @param Request $request
     * @param PlayLoadService $service
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(Request $request, PlayLoadService $service): Response
    {
        $service->negociate($request);
        return new Response(null, Response::HTTP_OK);
    }
}
