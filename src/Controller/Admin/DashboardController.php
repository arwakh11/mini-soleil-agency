<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[IsGranted(
    new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_MANAGER") or is_granted("ROLE_PHOTOGRAPHER")'),
    message: 'You are not allowed to access this page.'
)]
#[AdminDashboard(routePath: '/shamsadmin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct() {}

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getUserIdentifier())
            // use this method if you don't want to display the name of the user
            ->displayUserName(true)


            // you can hide the "Sign out" link from the user menu (e.g. when using
            // authentication methods like HTTP Basic or OAuth that don't support logout)
            // ->disableLogoutLink()

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                // MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                // MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                // the parent method already adds a "Sign out" item, so there's no need to add it here
                // MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }


    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Minisoleil');
    }


    public function configureMenuItems(): iterable
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER') || $this->isGranted('ROLE_PHOTOGRAPHER')) {

            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            yield MenuItem::linkTo(QuoteRequestCrudController::class, 'Quote Requests', 'fas fa-envelope')->setAction('index');
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            yield MenuItem::linkTo(ClientCrudController::class, 'Clients', 'fas fa-user-tie')->setAction('index');
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER') || $this->isGranted('ROLE_PHOTOGRAPHER')) {
            yield MenuItem::linkTo(ProjectsCrudController::class, 'Projects', 'fas fa-folder')->setAction('index');
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            yield MenuItem::linkTo(TeamMembersCrudController::class, 'Team Members', 'fas fa-people-group')->setAction('index');
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            yield MenuItem::linkTo(ModelsCrudController::class, 'Models', 'fas fa-star')->setAction('index');
        }
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MANAGER')) {
            yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fas fa-user-gear')->setAction('index');
        }
    }
}
