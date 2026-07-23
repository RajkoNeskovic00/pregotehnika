<?php

namespace App\Controller;

use App\Repository\NavMenuRepository;
use App\Repository\ProductRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    private ProductRepository $productRepository;

    public function __construct(
        NavMenuRepository $navMenuRepo,
        CategoryRepository $categoryRepo,
        SettingRepository $settingRepo,
        ProductRepository $productRepository
    ) {
        parent::__construct(
            $navMenuRepo,
            $categoryRepo,
            $settingRepo
        );

        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/products", name="products")
     */
    public function index(): Response
    {
        $categories = $this->categoryRepo->findMainCategories();

        return $this->renderPage(
            'product/index.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * @Route("/products/{slug}", name="product_category")
     */
    public function category(string $slug): Response
    {
        $category = $this->categoryRepo->findOneBy([
            'slug' => $slug,
        ]);

        if (!$category) {
            throw $this->createNotFoundException('Kategorija proizvoda nije pronađena.');
        }

        $products = $this->productRepository->findActiveByCategory($category->getId());

        return $this->renderPage(
            'product/category.html.twig',
            [
                'category' => $category,
                'products' => $products,
            ]
        );
    }

    /**
     * @Route("/products/{categorySlug}/{slug}", name="product_detail")
     */
    public function detail(string $categorySlug, string $slug): Response
    {
        $product = $this->productRepository->findActiveBySlug($slug);

        if (!$product || $product->getCategory()->getSlug() !== $categorySlug) {
            throw $this->createNotFoundException('Proizvod nije pronađen.');
        }

        return $this->renderPage(
            'product/detail.html.twig',
            [
                'product'  => $product,
                'category' => $product->getCategory(),
            ]
        );
    }
}
