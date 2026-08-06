<?php

namespace App\Controller;

use App\Repository\NavMenuRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use App\Service\LocalizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected NavMenuRepository $navMenuRepo;
    protected CategoryRepository $categoryRepo;
    protected SettingRepository $settingRepo;
    protected LocalizationService $localization;

    public function __construct(
        NavMenuRepository $navMenuRepo,
        CategoryRepository $categoryRepo,
        SettingRepository $settingRepo,
        LocalizationService $localization,
    ) {
        $this->navMenuRepo = $navMenuRepo;
        $this->categoryRepo = $categoryRepo;
        $this->settingRepo = $settingRepo;
        $this->localization = $localization;
    }

    protected function getNavItems(): array
    {
        $mainMenu = $this->navMenuRepo->findMainMeni();
        $navItems = [];

        foreach ($mainMenu as $menu) {
            $children = [];

            if ('products' === $menu->getSlug()) {
                $categories = $this->categoryRepo->findMainCategories();
                foreach ($categories as $category) {
                    $products = [];
                    $categorySlug = $this->localization->slug($category);
                    $categorySrSlug = $category->getSlug();
                    $categoryEnSlug = $category->getSlugEn();

                    foreach ($category->getProducts() as $product) {
                        // Skip "category overview" products that mirror the category slug.
                        if (
                            $product->getSlug() === $categorySrSlug
                            || ($categoryEnSlug && $product->getSlugEn() === $categoryEnSlug)
                            || ($categoryEnSlug && $product->getSlug() === $categoryEnSlug)
                        ) {
                            continue;
                        }

                        if (!$product->getIsActive()) {
                            continue;
                        }

                        $products[] = [
                            'title' => $this->localization->field($product, 'name'),
                            'slug'  => $this->localization->slug($product),
                        ];
                    }

                    $children[] = [
                        'title'    => $this->localization->field($category, 'name'),
                        'slug'     => $categorySlug,
                        'iTag'     => $category->getITag(),
                        'products' => $products,
                    ];
                }
            }

            $navItems[] = [
                'title'    => $this->localization->field($menu, 'name'),
                'slug'     => $menu->getSlug(),
                'route'    => $this->resolveNavRoute($menu->getSlug()),
                'children' => $children,
            ];
        }

        return $navItems;
    }

    protected function resolveNavRoute(?string $slug): ?string
    {
        return match ($slug) {
            '/', '', 'home' => 'home',
            'about'         => 'about',
            'products'      => 'products',
            'contact'       => 'contact',
            'faq'           => 'faq',
            default         => null,
        };
    }

    protected function getFooterData(): array
    {
        return [
            'settings' => $this->settingRepo->findAll(),
        ];
    }

    protected function getGlobalData(): array
    {
        return [
            'nav_items' => $this->getNavItems(),
            'footer'    => $this->getFooterData(),
        ];
    }

    protected function renderPage(
        string $view,
        array $parameters = [],
    ) {
        return $this->render(
            $view,
            array_merge(
                $this->getGlobalData(),
                $parameters,
            ),
        );
    }
}
