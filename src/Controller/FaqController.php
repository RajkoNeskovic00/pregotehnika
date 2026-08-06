<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends BaseController
{
    #[Route(path: [
        'sr' => '/faq',
        'en' => '/faq',
    ], name: 'faq')]
    public function index(FaqRepository $faqRepository): Response
    {
        return $this->renderPage('faq/index.html.twig', [
            'faqs' => $faqRepository->findActive(),
        ]);
    }
}
