<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TelegramController
 * @package App\Controller\Auth
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TelegramController extends AbstractController
{
    /**
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(): Response
    {
        return $this->render("login.html.twig");
    }

    /**
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function login(Request $request): Response
    {
        dd($request);
        return new Response(null, Response::HTTP_OK);
    }
}
