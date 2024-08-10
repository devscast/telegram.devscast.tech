<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * class DefaultController.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
#[AsController]
final class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(): Response
    {
        return new RedirectResponse('https://t.me/devscast');
    }

    #[Route('/hello', name: 'app_hello', methods: ['GET'])]
    public function hello(): Response
    {
        return new Response('Hello, World!');
    }
}
