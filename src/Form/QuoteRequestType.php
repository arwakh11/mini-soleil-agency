<?php

namespace App\Form;

use App\Entity\QuoteRequest;
use App\Enum\ContactPreference;
use App\Enum\MediaType;
use App\Enum\ShootType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;


class QuoteRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'quote_request.fields.full_name',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'quote_request.errors.full_name_required',
                    ]),
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'quote_request.fields.phone_number',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'quote_request.errors.phone_number_required',
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'quote_request.fields.email',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'quote_request.errors.email_required',
                    ]),
                    new Email([
                        'message' => 'quote_request.errors.email_invalid',
                    ]),
                ],
            ])
            ->add('shootType', ChoiceType::class, [
                'label' => 'quote_request.fields.shoot_type.label',
                'choices' => [
                    'quote_request.fields.shoot_type.choices.personal' => ShootType::PERSONAL,
                    'quote_request.fields.shoot_type.choices.brand' => ShootType::BRAND,
                ],
            ])->add('brandName', TextType::class, [
                'label' => 'quote_request.fields.brand_name.label',
                'required' => false,
            ])
            ->add('instagramAccount', TextType::class, [
                'label' => 'quote_request.fields.instagram_account.label',
                'required' => false,
            ])
            ->add('contactPreference', ChoiceType::class, [
                'label' => 'quote_request.fields.contact_preference.label',
                'choices' => [
                    'quote_request.fields.contact_preference.choices.email' => ContactPreference::EMAIL,
                    'quote_request.fields.contact_preference.choices.whatsapp' => ContactPreference::WHATSAPP,
                ],
            ])->add('mediaType', ChoiceType::class, [
                'label' => 'quote_request.fields.media_type.label',
                'choices' => [
                    'quote_request.fields.media_type.choices.photos' => MediaType::PHOTOS,
                    'quote_request.fields.media_type.choices.videos' => MediaType::VIDEOS,
                    'quote_request.fields.media_type.choices.both' => MediaType::BOTH,
                ],
            ])
            ->add('contentQuantity', TextType::class, [
                'label' => 'quote_request.fields.content_quantity.label',
                'attr' => [
                    'placeholder' => 'quote_request.fields.content_quantity.help',
                ],
                'required' => false,
            ])
            ->add('numberOfItems', TextType::class, [
                'label' => 'quote_request.fields.number_of_items.label',
                'attr' => [
                    'placeholder' => 'quote_request.fields.number_of_items.help',
                ],
                'required' => false,
            ])
            ->add('productType', TextType::class, [
                'label' => 'quote_request.fields.product_type.label',
                'attr' => [
                    'placeholder' => 'quote_request.fields.product_type.help',
                ],
                'required' => false,
            ])
            ->add('creativeDescription', TextareaType::class, [
                'label' => 'quote_request.fields.creative_description.label',
                'attr' => [
                    'placeholder' => 'quote_request.fields.creative_description.help',
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'quote_request.errors.creative_description_required',
                    ]),
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => 'quote_request.fields.start_date.label',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTimeImmutable('tomorrow'))->format('Y-m-d'),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'quote_request.submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuoteRequest::class,
        ]);
    }
}
