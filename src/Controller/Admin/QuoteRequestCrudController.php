<?php

namespace App\Controller\Admin;

use App\Entity\QuoteRequest;
use App\Enum\ContactPreference;
use App\Enum\MediaType;
use App\Enum\QuoteRequestStatus;
use App\Enum\ShootType;
use App\Services\QuoteRequestAcceptanceService;
use App\Services\QuoteRequestRejectionService;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;



#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER")'),
    message: 'You are not allowed to access this page.'
)]
class QuoteRequestCrudController extends AbstractCrudController
{

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,

        private QuoteRequestAcceptanceService $acceptanceService,
        private QuoteRequestRejectionService $rejectionService
    ) {}

    public static function getEntityFqcn(): string
    {
        return QuoteRequest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Quote Request')
            ->setEntityLabelInPlural('Quote Requests')
            ->setPageTitle(Crud::PAGE_INDEX, 'Quote Requests')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Quote Request Details')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Quote Request')
            ->setPageTitle(Crud::PAGE_NEW, 'Create Quote Request');
    }

    public function configureActions(Actions $actions): Actions
    {
        $acceptAction = Action::new(
            'accept',
            'Accept',
            'fa fa-check'
        )
            ->linkToCrudAction('accept')
            ->addCssClass('btn-success')
            ->askConfirmation(
                'Are you sure you want to accept this quote request?',
                'Yes, accept'
            );

        $rejectAction = Action::new(
            'reject',
            'Reject',
            'fa fa-close'
        )
            ->linkToCrudAction('reject')
            ->addCssClass('btn-danger')
            ->askConfirmation(
                'Are you sure you want to reject this quote request?',
                'Yes, reject'
            );

        $actions
            // Index
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $acceptAction)
            ->add(Crud::PAGE_INDEX, $rejectAction)

            // Detail
            ->add(Crud::PAGE_DETAIL, $acceptAction)
            ->add(Crud::PAGE_DETAIL, $rejectAction);

        // Only Admin can delete from the detail page
        if (!$this->isGranted('ROLE_ADMIN')) {
            $actions = $actions
                ->remove(Crud::PAGE_DETAIL, Action::DELETE);
        }

        return $actions;
    }

    #[AdminRoute('/quote-request/{id}/accept', name: 'quote_request_accept')]
    public function accept(): Response
    {
        $quoteRequest = $this->getContext()->getEntity()->getInstance();

        $this->acceptanceService->accept($quoteRequest);

        $this->addFlash(
            'success',
            'Quote request has been accepted successfully.'
        );
        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($quoteRequest->getId())
            ->generateUrl();

        return $this->redirect($url);
    }


    #[AdminRoute('/quote-request/{id}/reject', name: 'quote_request_reject')]
    public function reject(): Response
    {
        $quoteRequest = $this->getContext()->getEntity()->getInstance();

        $this->rejectionService->reject($quoteRequest);

        $this->addFlash(
            'success',
            'Quote request has been rejected.'
        );

        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($quoteRequest->getId())
            ->generateUrl();

        return $this->redirect($url);
    }


    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices([
                'Pending' => QuoteRequestStatus::PENDING,
                'Accepted' => QuoteRequestStatus::ACCEPTED,
                'Rejected' => QuoteRequestStatus::REJECTED,
            ]))
            ->add(ChoiceFilter::new('shootType')->setChoices([
                'Personal shoot' => ShootType::PERSONAL,
                'Brand shoot' => ShootType::BRAND
            ]))
            ->add(DateTimeFilter::new('created_at'))
            ->add(DateTimeFilter::new('startDate'))

        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            ChoiceField::new('status')->setChoices(QuoteRequestStatus::cases())
                ->renderAsBadges([
                    'Accepted' => 'success',
                    'Rejected' => 'danger',
                    'Pending' => 'warning',
                ]),
            TextField::new('fullName'),
            TextField::new('email')->hideOnIndex(),
            TextField::new('phoneNumber')->hideOnIndex(),
            ChoiceField::new('shootType')->setChoices(ShootType::cases()),
            TextField::new('brandName'),
            TextField::new('instagramAccount')->hideOnIndex(),
            TextField::new('contentQuantity')->hideOnIndex(),
            TextField::new('numberOfItems')->hideOnIndex(),
            TextField::new('productType'),
            ChoiceField::new('contactPreference')->setChoices(ContactPreference::cases())->hideOnIndex(),
            ChoiceField::new('mediaType')->setChoices(MediaType::cases()),
            DateField::new('startDate'),
            TextareaField::new('creativeDescription')->hideOnIndex(),

        ];
    }
}
