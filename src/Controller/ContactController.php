<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Service\ContactService;
use App\Form\ContactMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends BaseController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, ContactService $contactService): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $result = $contactService->save($contactMessage);
                if ($result) {
                    $this->addFlash('success', 'Vaša poruka je uspešno poslata. Kontaktiraćemo vas u najkraćem roku.');

                    return $this->redirectToRoute('contact');
                }

                $this->addFlash('error', 'Došlo je do greške prilikom slanja poruke.');
            }
        }

        return $this->render(
            'contact/index.html.twig',
            array_merge(
                $this->getGlobalData(),
                [
                    'form' => $form->createView(),
                ],
            ),
        );
    }
}
