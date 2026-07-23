<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivacyController extends BaseController
{
    #[Route('/politika-privatnosti', name: 'privacy_policy')]
    public function index(): Response
    {
        return $this->renderPage('fixed/privacy.html.twig');
    }
}
