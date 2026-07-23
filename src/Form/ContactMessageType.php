<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full_name', TextType::class, [
                'label'    => 'Ime i prezime / Naziv Firme',
                'required' => true,
                'attr'     => [
                    'placeholder' => 'Unesite ime i prezime / Naziv firme',
                ],
            ])
            ->add('email', EmailType::class, [
                'label'    => 'Email',
                'required' => true,
                'attr'     => [
                    'placeholder' => 'Unesite email adresu',
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label'    => 'Telefon',
                'required' => true,
                'attr'     => [
                    'placeholder' => 'Unesite broj telefona',
                ],
            ])
            ->add('message', TextareaType::class, [
                'label'    => 'Poruka',
                'required' => true,
                'attr'     => [
                    'placeholder' => 'Unesite vašu poruku',
                    'rows'        => 6,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
