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
            IdField::new('id')->onlyOnIndex(),

            TextField::new('name', 'Naziv (SR)')->onlyOnIndex(),
            AssociationField::new('category', 'Kategorija')->onlyOnIndex(),
            BooleanField::new('isActive', 'Aktivan')->onlyOnIndex(),

            TextField::new('name', 'Naziv (SR)')->onlyOnForms(),
            TextField::new('nameEn', 'Naziv (EN)')->onlyOnForms(),
            TextField::new('slug', 'Slug (SR)')->onlyOnForms(),
            TextField::new('slugEn', 'Slug (EN)')->onlyOnForms(),

            TextEditorField::new('shortDescription', 'Kratak opis (SR)')->onlyOnForms(),
            TextEditorField::new('shortDescriptionEn', 'Kratak opis (EN)')->onlyOnForms(),
            TextEditorField::new('description', 'Opis (SR)')->onlyOnForms(),
            TextEditorField::new('descriptionEn', 'Opis (EN)')->onlyOnForms(),

            AssociationField::new('category', 'Kategorija')->onlyOnForms(),
            BooleanField::new('isActive', 'Aktivan')->onlyOnForms(),
            IntegerField::new('position', 'Redosled')->onlyOnForms(),

            TextField::new('metaTitle', 'SEO Title (SR)')->onlyOnForms(),
            TextField::new('metaTitleEn', 'SEO Title (EN)')->onlyOnForms(),
            TextField::new('metaDescription', 'SEO Description (SR)')->onlyOnForms(),
            TextField::new('metaDescriptionEn', 'SEO Description (EN)')->onlyOnForms(),
            TextField::new('metaKeywords', 'SEO Keywords (SR)')->onlyOnForms(),
            TextField::new('metaKeywordsEn', 'SEO Keywords (EN)')->onlyOnForms(),

            TextField::new('name', 'Naziv (SR)')->onlyOnDetail(),
            TextField::new('nameEn', 'Naziv (EN)')->onlyOnDetail(),
            TextField::new('slug', 'Slug (SR)')->onlyOnDetail(),
            TextField::new('slugEn', 'Slug (EN)')->onlyOnDetail(),
            TextEditorField::new('shortDescription', 'Kratak opis (SR)')->onlyOnDetail(),
            TextEditorField::new('shortDescriptionEn', 'Kratak opis (EN)')->onlyOnDetail(),
            TextEditorField::new('description', 'Opis (SR)')->onlyOnDetail(),
            TextEditorField::new('descriptionEn', 'Opis (EN)')->onlyOnDetail(),
            AssociationField::new('category', 'Kategorija')->onlyOnDetail(),
            BooleanField::new('isActive', 'Aktivan')->onlyOnDetail(),
            IntegerField::new('position', 'Redosled')->onlyOnDetail(),
            TextField::new('metaTitle', 'SEO Title (SR)')->onlyOnDetail(),
            TextField::new('metaTitleEn', 'SEO Title (EN)')->onlyOnDetail(),
            TextField::new('metaDescription', 'SEO Description (SR)')->onlyOnDetail(),
            TextField::new('metaDescriptionEn', 'SEO Description (EN)')->onlyOnDetail(),
            TextField::new('metaKeywords', 'SEO Keywords (SR)')->onlyOnDetail(),
            TextField::new('metaKeywordsEn', 'SEO Keywords (EN)')->onlyOnDetail(),

            CollectionField::new('images', 'Slike')
                ->onlyOnDetail()
                ->setTemplatePath('admin/product/images.html.twig'),

            CollectionField::new('documents', 'Dokumenti')
                ->onlyOnDetail()
                ->setTemplatePath('admin/product/documents.html.twig'),

            DateTimeField::new('createdAt', 'Kreiran')
                ->onlyOnDetail()
                ->setFormat('dd.MM.yyyy HH:mm'),

            DateTimeField::new('updatedAt', 'Ažuriran')
                ->onlyOnDetail()
                ->setFormat('dd.MM.yyyy HH:mm'),
        ];
    }
}
