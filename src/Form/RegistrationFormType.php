<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.errors.email_required',
                    ]),
                    new Email([
                        'message' => 'register.errors.email_invalid',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class,
                'mapped' => false,

                'first_options' => [
                    'label' => false,

                ],

                'second_options' => [
                    'label' => false,

                ],


                'invalid_message' => 'register.errors.password_mismatch',

                'constraints' => [
                    new NotBlank([
                        'message' => 'register.errors.password_required',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'register.errors.password_too_short',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
