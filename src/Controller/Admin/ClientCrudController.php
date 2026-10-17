<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Enum\ContactPreference;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;


#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER")'),
    message: 'You are not allowed to access this page.'
)]
class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureActions(Actions $actions): Actions
    {

        $actions
            // Index
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);


        // Only Admin can delete from the detail page
        if (!$this->isGranted('ROLE_ADMIN')) {
            $actions = $actions
                ->remove(Crud::PAGE_DETAIL, Action::DELETE);
        }
        // Only Admin and Manager can create new clients
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            $actions = $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('fullName'))
            ->add(TextFilter::new('email'))
            ->add(TextFilter::new('phoneNumber'))
            ->add(TextFilter::new('brandName'))
            ->add(TextFilter::new('instagramAccount'));
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('fullName'),
            TextField::new('email'),
            TextField::new('phoneNumber'),
            TextField::new('brandName'),
            TextField::new('instagramAccount'),
            ChoiceField::new('contactPreference')->setChoices(ContactPreference::cases()),
        ];
    }
}
