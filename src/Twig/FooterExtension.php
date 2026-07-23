<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Repository\NavMenuRepository;
use App\Repository\SettingRepository;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;

class FooterExtension extends AbstractExtension
{
    private CategoryRepository $categoryRepository;
    private NavMenuRepository $navMenuRepository;
    private SettingRepository $settingRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        NavMenuRepository $navMenuRepository,
        SettingRepository $settingRepository,
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->navMenuRepository = $navMenuRepository;
        $this->settingRepository = $settingRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('footer_data', [$this, 'getFooterData']),
        ];
    }

    public function getFooterData(): array
    {
        return [
            'menu'       => $this->navMenuRepository->findMainMeni(),
            'categories' => $this->categoryRepository->findMainCategories(),
            'settings'   => $this->settingRepository->getAll(),
        ];
    }
}
