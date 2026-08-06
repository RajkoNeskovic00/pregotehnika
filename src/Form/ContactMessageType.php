<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactMessageType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full_name', TextType::class, [
                'label'    => 'contact.form.full_name',
                'required' => true,
                'attr'     => [
                    'placeholder' => $this->translator->trans('contact.form.full_name_placeholder'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label'    => 'contact.form.email',
                'required' => true,
                'attr'     => [
                    'placeholder' => $this->translator->trans('contact.form.email_placeholder'),
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label'    => 'contact.form.phone',
                'required' => true,
                'attr'     => [
                    'placeholder' => $this->translator->trans('contact.form.phone_placeholder'),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label'    => 'contact.form.message',
                'required' => true,
                'attr'     => [
                    'placeholder' => $this->translator->trans('contact.form.message_placeholder'),
                    'rows'        => 6,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => ContactMessage::class,
            'translation_domain' => 'messages',
        ]);
    }
}
