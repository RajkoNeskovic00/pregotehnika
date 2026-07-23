<?php

namespace App\Controller\Admin;

use App\Entity\Faq;
use App\Entity\User;
use App\Entity\NavMenu;
use App\Entity\Product;
use App\Entity\Setting;
use App\Entity\Category;
use App\Entity\ProductImage;
use App\Entity\ContactMessage;
use App\Entity\ProductDocument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $url = $adminUrlGenerator->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Stevanovic CMS');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Korisnici', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Kategorije', 'fa fa-tags', Category::class);
        yield MenuItem::linkToCrud('Proizvodi', 'fa fa-box', Product::class);
        yield MenuItem::linkToCrud('Proizvodi slike', 'fa fa-image', ProductImage::class);
        yield MenuItem::linkToCrud('Dokumenti proizvoda', 'fas fa-file-pdf', ProductDocument::class);
        yield MenuItem::linkToCrud('Podešavanja', 'fa fa-cogs', Setting::class);
        yield MenuItem::linkToCrud('Poruke', 'fa fa-envelope', ContactMessage::class);
        yield MenuItem::linkToCrud('Navigacije', 'fa fa-bars', NavMenu::class);
        yield MenuItem::linkToCrud('FAQ', 'fa fa-question-circle', Faq::class);
    }
}
