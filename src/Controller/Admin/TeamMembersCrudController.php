<?php

namespace App\Controller\Admin;

use App\Entity\TeamMembers;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;


#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER")'),
    message: 'You are not allowed to access this page.'
)]
class TeamMembersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeamMembers::class;
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
                ->remove(Crud::PAGE_DETAIL, Action::DELETE)
                ->remove(Crud::PAGE_DETAIL, Action::EDIT);
        }

        // Only Admin and Manager can create new team members
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            $actions = $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('name'))
            ->add(TextFilter::new('email'))
            ->add(TextFilter::new('phoneNumber'))
            ->add(TextFilter::new('price'))
            ->add(TextFilter::new('job'))
            ->add(TextFilter::new('details'));
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
