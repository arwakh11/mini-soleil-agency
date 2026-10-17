<?php

namespace App\Controller\Admin;

use App\Entity\Projects;
use App\Enum\MediaType;
use App\Enum\ShootType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;


#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER") or is_granted("ROLE_PHOTOGRAPHER")'),
    message: 'You are not allowed to access Projects.'
)]
class ProjectsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Projects::class;
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

        // Only Admin and Manager can create new projects
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_MANAGER')) {
            $actions = $actions
                ->remove(Crud::PAGE_INDEX, Action::NEW);
        }
        return $actions;
    }


    public function configureFilters(Filters $filters): Filters
    {

        return $filters
            ->add(ChoiceFilter::new('shootType')->setChoices([
                'Personal shoot' => ShootType::PERSONAL,
                'Brand shoot' => ShootType::BRAND
            ]))
            ->add(ChoiceFilter::new('mediaType')->setChoices([
                'Photos' => MediaType::PHOTOS,
                'Videos' => MediaType::VIDEOS,
                'Both' => MediaType::BOTH
            ]))
            ->add(DateTimeFilter::new('startDate'))
            ->add(EntityFilter::new('client')->setLabel('Client'))
            ->add(EntityFilter::new('teamMembers')->setLabel('Team Members'))
            ->add(EntityFilter::new('models')->setLabel('Models'))
            ->add(TextFilter::new('creativeDescription'))
            ->add(DateTimeFilter::new('created_at'))

        ;
    }


    public function configureFields(string $pageName): iterable
    {
        // Photographer can only edit the two photo folder fields
        if ($this->isGranted('ROLE_PHOTOGRAPHER') && $pageName === Crud::PAGE_EDIT) {
            return [
                TextField::new('rawFolderId'),
                TextField::new('editedFolderId'),
            ];
        }

        return [
            IdField::new('id')->onlyOnIndex(),

            AssociationField::new('client')
                ->setLabel('Client')
                ->autocomplete(),

            AssociationField::new('teamMembers')
                ->setLabel('Team Members')
                ->hideOnIndex()
                ->autocomplete()
                ->setTemplatePath('admin/field/projects/detail/teamMembers.html.twig'),

            AssociationField::new('models')
                ->setLabel('Models')
                ->hideOnIndex()
                ->autocomplete()
                ->setTemplatePath('admin/field/projects/detail/models.html.twig'),

            ChoiceField::new('shootType')
                ->setChoices(ShootType::cases()),

            TextField::new('contentQuantity')
                ->hideOnIndex(),

            TextField::new('numberOfItems')
                ->hideOnIndex(),

            TextField::new('productType')
                ->hideOnIndex(),

            ChoiceField::new('mediaType')
                ->setChoices(MediaType::cases()),

            DateField::new('startDate'),

            TextareaField::new('creativeDescription')
                ->hideOnIndex(),

            TextField::new('status'),
            TextField::new('rawFolderId'),
            TextField::new('editedFolderId'),
        ];
    }
}
