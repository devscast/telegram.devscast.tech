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
        return $this->render("@dashboard/module/auth/page/login.html.twig");
    }

    /**
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function login(Request $request): Response
    {
        $chatId = $request->query->getInt('id');
        $username = $request->query->get('username');
        $avatar = $request->query->get('photo_url');
        $hash = $request->query->get('hash');

        return $this->redirectToRoute('auth_login_telegram');
    }
}
