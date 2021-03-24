<?php

declare(strict_types=1);

namespace App\Controller\Webhook;

use App\Service\Github\PlayLoadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GithubWebhookController
 * @package App\Controller\Webhook
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class GithubWebhookController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function main(Request $request): Response
    {
        return $this->render('@dashboard/module/auth/page/login.html.twig');
    }

    /**
     * @param Request $request
     * @param PlayLoadService $service
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(Request $request, PlayLoadService $service): Response
    {
        $service->negotiate($request);
        return new Response(null, Response::HTTP_OK);
    }
}
