<?php

namespace App\Controller\Admin;

use App\Entity\Models;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Image;

#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER")'),
    message: 'You are not allowed to access this page.'
)]
class ModelsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Models::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->overrideTemplate('crud/index', 'admin/models/index.html.twig');
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

        // Only Admin and Manager can create new models
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
            ->add(TextFilter::new('instagram'))
            ->add(TextFilter::new('gender'))
            ->add(TextFilter::new('measurements'))
            ->add(TextFilter::new('details'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('photo')->setBasePath('uploads/')->setUploadDir('public/uploads/')->setFileConstraints(new Image(maxSize: '10M', mimeTypes: ['image/png', 'image/jpeg'])),
            TextField::new('name'),
            TextField::new('instagram'),
            TextField::new('email')->hideOnIndex(),
            TextField::new('phone'),
            TextField::new('price'),
            TextField::new('gender')->hideOnIndex(),
            TextareaField::new('measurements'),
            TextareaField::new('details')->hideOnIndex(),
        ];
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Get the original photo before the update
        $originalData = $entityManager
            ->getUnitOfWork()
            ->getOriginalEntityData($entityInstance);

        $oldPhoto = $originalData['photo'] ?? null;

        // Let EasyAdmin save the updated entity and upload the new photo
        parent::updateEntity($entityManager, $entityInstance);

        // Get the new photo filename
        $newPhoto = $entityInstance->getPhoto();

        // Delete the old photo if it has been replaced
        if ($oldPhoto && $oldPhoto !== $newPhoto) {
            $filePath = $this->getParameter('kernel.project_dir')
                . '/public/uploads/'
                . $oldPhoto;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $photo = $entityInstance->getPhoto();

        // Delete the physical photo
        if ($photo) {
            $filePath = $this->getParameter('kernel.project_dir')
                . '/public/uploads/'
                . $photo;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the database record
        parent::deleteEntity($entityManager, $entityInstance);
    }
}
