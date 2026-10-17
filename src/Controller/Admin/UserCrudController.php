<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\ExpressionLanguage\Expression;



#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER")'),
    message: 'You are not allowed to access this page.'
)]
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions
            // Index
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);


        // Only Admin can delete and edit from the detail page
        if (!$this->isGranted('ROLE_ADMIN')) {
            $actions = $actions
                ->remove(Crud::PAGE_DETAIL, Action::EDIT)
                ->remove(Crud::PAGE_DETAIL, Action::DELETE);
        }

        // Only Admin and Manager can create new users
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            $actions = $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isVerified'))
            ->add(TextFilter::new('email'))
            ->add(ArrayFilter::new('roles')->setChoices([
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                'Photographer' => 'ROLE_PHOTOGRAPHER',
                'Manager' => 'ROLE_MANAGER',
            ]));
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        yield TextField::new('email')
            ->setFormType(EmailType::class)
            ->setFormTypeOption('constraints', [
                new Assert\NotBlank([
                    'message' => 'Please enter an email address.',
                ]),
                new Assert\Email([
                    'message' => 'Please enter a valid email address.',
                ]),
            ]);

        yield ChoiceField::new('roles')
            ->setChoices([
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                'Photographer' => 'ROLE_PHOTOGRAPHER',
                'Manager' => 'ROLE_MANAGER',
            ])
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_USER' => 'secondary',
                'ROLE_ADMIN' => 'danger',
                'ROLE_PHOTOGRAPHER' => 'warning',
                'ROLE_MANAGER' => 'success',
            ]);

        if ($pageName === Crud::PAGE_NEW) {
            yield TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOption('type', PasswordType::class)
                ->setFormTypeOption('first_options', [
                    'label' => 'Password',
                    'required' => true,
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ])
                ->setFormTypeOption('second_options', [
                    'label' => 'Confirm password',
                    'required' => true,
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ])
                ->setFormTypeOption('invalid_message', 'The passwords do not match.')
                ->setFormTypeOption('constraints', [
                    new Assert\NotBlank([
                        'message' => 'Please enter a password.',
                    ]),
                    new Assert\Length(
                        min: 6,
                        minMessage: 'Your password must be at least {{ limit }} characters long.',
                    ),
                ]);
        }

        yield BooleanField::new('isVerified');
    }

    // public function persistEntity(
    //     EntityManagerInterface $entityManager,
    //     $entityInstance
    // ): void {
    //     if (!$entityInstance instanceof User) {
    //         return;
    //     }

    //     $hashedPassword = $this->passwordHasher->hashPassword(
    //         $entityInstance,
    //         $entityInstance->getPassword()
    //     );

    //     $entityInstance->setPassword($hashedPassword);

    //     parent::persistEntity($entityManager, $entityInstance);
    // }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $plaintextPassword = $entityInstance->getPassword();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $entityInstance,
            $plaintextPassword
        );

        $entityInstance->setPassword($hashedPassword);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
}
