<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Proizvod')
            ->setEntityLabelInPlural('Proizvodi')
            ->setPageTitle('index', 'Proizvodi');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // =====================
            // INDEX (KRATKO)
            // =====================
            IdField::new('id')->onlyOnIndex(),

            TextField::new('name', 'Naziv')->onlyOnIndex(),
            AssociationField::new('category', 'Kategorija')->onlyOnIndex(),
            BooleanField::new('isActive', 'Aktivan')->onlyOnIndex(),

            // =====================
            // FORM (CREATE + EDIT)
            // =====================
            TextField::new('name', 'Naziv')->onlyOnForms(),
            TextField::new('slug', 'Slug')->onlyOnForms(),

            TextEditorField::new('shortDescription', 'Kratak opis')->onlyOnForms(),
            TextEditorField::new('description', 'Opis')->onlyOnForms(),

            AssociationField::new('category', 'Kategorija')->onlyOnForms(),
            BooleanField::new('isActive', 'Aktivan')->onlyOnForms(),
            IntegerField::new('position', 'Redosled')->onlyOnForms(),

            TextField::new('metaTitle', 'SEO Title')->onlyOnForms(),
            TextField::new('metaDescription', 'SEO Description')->onlyOnForms(),
            TextField::new('metaKeywords', 'SEO Keywords')->onlyOnForms(),

            // =====================
            // DETAIL (FULL VIEW)
            // =====================

            TextField::new('name', 'Naziv')->onlyOnDetail(),

            TextField::new('slug', 'Slug')->onlyOnDetail(),

            TextEditorField::new('shortDescription', 'Kratak opis')->onlyOnDetail(),

            TextEditorField::new('description', 'Opis')->onlyOnDetail(),

            AssociationField::new('category', 'Kategorija')->onlyOnDetail(),

            BooleanField::new('isActive', 'Aktivan')->onlyOnDetail(),

            IntegerField::new('position', 'Redosled')->onlyOnDetail(),

            TextField::new('metaTitle', 'SEO Title')->onlyOnDetail(),
            TextField::new('metaDescription', 'SEO Description')->onlyOnDetail(),
            TextField::new('metaKeywords', 'SEO Keywords')->onlyOnDetail(),

            // =====================
            // MEDIA
            // =====================
            CollectionField::new('images', 'Slike')
                ->onlyOnDetail()
                ->setTemplatePath('admin/product/images.html.twig'),

            CollectionField::new('documents', 'Dokumenti')
                ->onlyOnDetail()
                ->setTemplatePath('admin/product/documents.html.twig'),

            // =====================
            // META
            // =====================
            DateTimeField::new('createdAt', 'Kreiran')
                ->onlyOnDetail()
                ->setFormat('dd.MM.yyyy HH:mm'),

            DateTimeField::new('updatedAt', 'Ažuriran')
                ->onlyOnDetail()
                ->setFormat('dd.MM.yyyy HH:mm'),
        ];
    }
}
