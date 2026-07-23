<?php

namespace App\Controller\Admin;

use App\Entity\NavMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class NavMenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NavMenu::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $actions
                ->disable(
                    Action::NEW,
                    Action::EDIT,
                    Action::DELETE,
                );
        }

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Navigaciju')
            ->setEntityLabelInPlural('Navigacija')
            ->setPageTitle('index', 'Navigacija');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('slug'),
            IntegerField::new('order_num'),
        ];
    }
}
