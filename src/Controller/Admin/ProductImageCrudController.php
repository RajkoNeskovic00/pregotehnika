<?php

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sliku')
            ->setEntityLabelInPlural('Proizvodi slike')
            ->setPageTitle('index', 'Proizvodi slike');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product')->autocomplete(),

            // =========================
            // UPLOAD IMAGE
            // =========================
            ImageField::new('imagePath', 'Slika')
                ->setBasePath('/uploads/products')
                ->setUploadDir('public/uploads/products')
                ->setUploadedFileNamePattern('[slug]-[uuid].[extension]')
                ->setRequired(true),

            TextField::new('altText', 'Alt tekst'),

            IntegerField::new('position', 'Redosled'),

            BooleanField::new('isMain', 'Glavna slika'),
        ];
    }
}
