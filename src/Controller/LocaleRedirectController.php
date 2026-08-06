<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocaleRedirectController extends AbstractController
{
    #[Route('/', name: 'root_redirect')]
    public function redirectToDefaultLocale(): RedirectResponse
    {
        return $this->redirectToRoute('home', ['_locale' => 'sr'], Response::HTTP_FOUND);
    }
}
