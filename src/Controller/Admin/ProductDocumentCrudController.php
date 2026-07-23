<?php

namespace App\Controller\Admin;

use App\Entity\ProductDocument;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductDocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductDocument::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Dokument')
            ->setEntityLabelInPlural('Proizvodi dokumenta')
            ->setPageTitle('index', 'Proizvodi dokumenta');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product', 'Proizvod')
                ->autocomplete(),

            TextField::new('filePath', 'Naziv'),

            TextField::new('filePath', 'Preuzmi dokument')
                ->onlyOnIndex()
                ->setTemplatePath('admin/product/download.html.twig'),

            Field::new('fileUpload', 'Dokument')
                ->setFormType(FileType::class)
                ->onlyOnForms(),

            IntegerField::new('position', 'Redosled'),
        ];
    }
}
