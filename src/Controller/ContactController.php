<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Service\ContactService;
use App\Form\ContactMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends BaseController
{
    #[Route(path: [
        'sr' => '/kontakt',
        'en' => '/contact',
    ], name: 'contact')]
    public function index(
        Request $request,
        ContactService $contactService,
        TranslatorInterface $translator,
    ): Response {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $result = $contactService->save($contactMessage);
                if ($result) {
                    $this->addFlash('success', $translator->trans('contact.flash.success'));

                    return $this->redirectToRoute('contact');
                }

                $this->addFlash('error', $translator->trans('contact.flash.error'));
            }
        }

        return $this->renderPage('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
