<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends BaseController
{
    #[Route(path: [
        'sr' => '/o-nama',
        'en' => '/about',
    ], name: 'about')]
    public function index(): Response
    {
        return $this->renderPage('about/index.html.twig');
    }
}
