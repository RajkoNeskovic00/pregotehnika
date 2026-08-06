<?php

namespace App\Controller;

use App\Repository\NavMenuRepository;
use App\Repository\ProductRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Service\LocalizationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductController extends BaseController
{
    private ProductRepository $productRepository;

    public function __construct(
        NavMenuRepository $navMenuRepo,
        CategoryRepository $categoryRepo,
        SettingRepository $settingRepo,
        LocalizationService $localization,
        ProductRepository $productRepository,
    ) {
        parent::__construct(
            $navMenuRepo,
            $categoryRepo,
            $settingRepo,
            $localization,
        );

        $this->productRepository = $productRepository;
    }

    #[Route(path: [
        'sr' => '/proizvodi',
        'en' => '/products',
    ], name: 'products')]
    public function index(): Response
    {
        $categories = $this->categoryRepo->findMainCategories();

        return $this->renderPage('product/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route(path: [
        'sr' => '/proizvodi/{slug}',
        'en' => '/products/{slug}',
    ], name: 'product_category')]
    public function category(string $slug, Request $request, TranslatorInterface $translator): Response
    {
        $locale = $request->getLocale();
        $category = $this->categoryRepo->findOneByLocalizedSlug($slug, $locale);

        if (!$category) {
            throw $this->createNotFoundException($translator->trans('products.not_found_category'));
        }

        $canonicalSlug = $this->localization->slug($category, $locale);
        if ($canonicalSlug && $canonicalSlug !== $slug) {
            return new RedirectResponse(
                $this->generateUrl('product_category', [
                    '_locale' => $locale,
                    'slug'    => $canonicalSlug,
                ]),
                Response::HTTP_MOVED_PERMANENTLY,
            );
        }

        $products = $this->productRepository->findActiveByCategory($category->getId());

        return $this->renderPage('product/category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route(path: [
        'sr' => '/proizvodi/{categorySlug}/{slug}',
        'en' => '/products/{categorySlug}/{slug}',
    ], name: 'product_detail')]
    public function detail(string $categorySlug, string $slug, Request $request, TranslatorInterface $translator): Response
    {
        $locale = $request->getLocale();
        $product = $this->productRepository->findActiveByLocalizedSlug($slug, $locale);

        if (!$product) {
            throw $this->createNotFoundException($translator->trans('products.not_found_product'));
        }

        $category = $product->getCategory();
        $canonicalCategorySlug = $this->localization->slug($category, $locale);
        $canonicalProductSlug = $this->localization->slug($product, $locale);

        if (
            !$this->localization->matchesLocalizedSlug($category, $categorySlug, $locale)
            || $canonicalCategorySlug !== $categorySlug
            || $canonicalProductSlug !== $slug
        ) {
            return new RedirectResponse(
                $this->generateUrl('product_detail', [
                    '_locale'      => $locale,
                    'categorySlug' => $canonicalCategorySlug,
                    'slug'         => $canonicalProductSlug,
                ]),
                Response::HTTP_MOVED_PERMANENTLY,
            );
        }

        return $this->renderPage('product/detail.html.twig', [
            'product'  => $product,
            'category' => $category,
        ]);
    }
}
