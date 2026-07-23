<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ContactService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private UserRepository $userRepository,
    ) {
    }

    public function save(ContactMessage $contactMessage): bool
    {
        $this->entityManager->beginTransaction();

        try {
            $this->entityManager->persist($contactMessage);
            $this->entityManager->flush();

            $this->entityManager->commit();

            $this->sendNotificationEmail($contactMessage);

            return true;
        } catch (\Throwable $e) {
            $this->entityManager->rollback();

            return false;
        }
    }

    private function sendNotificationEmail(ContactMessage $contactMessage): void
    {
        $admins = $this->userRepository->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%')
            ->getQuery()
            ->getResult();

        $adminEmails = [];
        foreach ($admins as $admin) {
            if ($admin->getEmail()) {
                $adminEmails[] = $admin->getEmail();
            }
        }

        if (empty($adminEmails)) {
            $adminEmails = ['test@example.com'];
        }

        $email = (new TemplatedEmail())
            ->from('noreply@pregotehnika.rs') // ovo se mora promeniti
            ->to(...$adminEmails)
            ->subject('Nova poruka sa kontakt forme - Pregotehnika')
            ->htmlTemplate('emails/contact_notification.html.twig')
            ->context([
                'message' => $contactMessage,
            ]);

        $this->mailer->send($email); // mora da se proveri da li je mejl uspesno poslat
    }
}
