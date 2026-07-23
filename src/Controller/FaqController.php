<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FaqController extends BaseController
{
    /**
     * @Route("/faq", name="faq")
     */
    public function index(FaqRepository $faqRepository): Response
    {
        return $this->render('faq/index.html.twig', array_merge(
            $this->getGlobalData(),
            [
                'faqs' => $faqRepository->findActive(),
            ],
        ));
    }
}
