<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class DashboardController extends AbstractDashboardController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Neoflips2');

    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil Site', 'fa fa-home','app_accueil');
        yield MenuItem::linkToCrud('Product Manager', 'fas fa-list', Product::class);
        yield MenuItem::linkToCrud('Users Manager', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Category Manager', 'fas fa-list', Category::class);
    }
}
