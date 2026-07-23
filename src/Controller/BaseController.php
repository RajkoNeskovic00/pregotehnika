<?php

namespace App\Controller;

use App\Repository\NavMenuRepository;
use App\Repository\SettingRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected NavMenuRepository $navMenuRepo;
    protected CategoryRepository $categoryRepo;
    protected SettingRepository $settingRepo;

    public function __construct(
        NavMenuRepository $navMenuRepo,
        CategoryRepository $categoryRepo,
        SettingRepository $settingRepo,
    ) {
        $this->navMenuRepo = $navMenuRepo;
        $this->categoryRepo = $categoryRepo;
        $this->settingRepo = $settingRepo;
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
                    $categorySlug = $category->getSlug();

                    foreach ($category->getProducts() as $product) {
                        if ($product->getSlug() === $categorySlug) {
                            continue;
                        }

                        $products[] = [
                            'title' => $product->getName(),
                            'slug'  => $product->getSlug(),
                        ];
                    }

                    $children[] = [
                        'title'    => $category->getName(),
                        'slug'     => $category->getSlug(),
                        'iTag'     => $category->getITag(),
                        'products' => $products,
                    ];
                }
            }

            $navItems[] = [
                'title'    => $menu->getName(),
                'slug'     => $menu->getSlug(),
                'children' => $children,
            ];
        }

        return $navItems;
    }

    /**
     * Footer podaci.
     */
    protected function getFooterData(): array
    {
        return [
            'settings' => $this->settingRepo->findAll(),
        ];
    }

    /**
     * Svi globalni Twig podaci.
     */
    protected function getGlobalData(): array
    {
        return [
            'nav_items' => $this->getNavItems(),
            'footer'    => $this->getFooterData(),
        ];
    }

    /**
     * Render svih stranica.
     */
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
