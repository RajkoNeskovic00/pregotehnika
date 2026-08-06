<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocalizationService
{
    public function __construct(
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository,
    ) {
    }

    public function getLocale(): string
    {
        return $this->requestStack->getCurrentRequest()?->getLocale() ?: 'sr';
    }

    /**
     * Returns the English field value when locale is en and that value is non-empty;
     * otherwise returns the default (Serbian) field value.
     */
    public function field(object $entity, string $field, ?string $locale = null): ?string
    {
        $locale ??= $this->getLocale();
        $suffix = ucfirst($field);

        if ('en' === $locale) {
            $enGetter = 'get'.$suffix.'En';
            if (method_exists($entity, $enGetter)) {
                $value = $entity->{$enGetter}();
                if (null !== $value && '' !== trim(strip_tags((string) $value))) {
                    return $value;
                }
            }
        }

        $getter = 'get'.$suffix;
        if (!method_exists($entity, $getter)) {
            return null;
        }

        return $entity->{$getter}();
    }

    public function slug(object $entity, ?string $locale = null): ?string
    {
        $locale ??= $this->getLocale();

        if ('en' === $locale && method_exists($entity, 'getSlugEn')) {
            $slugEn = $entity->getSlugEn();
            if (null !== $slugEn && '' !== $slugEn) {
                return $slugEn;
            }
        }

        return method_exists($entity, 'getSlug') ? $entity->getSlug() : null;
    }

    /**
     * Generate absolute URL for the current page in another locale,
     * remapping product/category slugs when needed.
     */
    public function alternateUrl(string $locale, int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            return null;
        }

        $route = $request->attributes->get('_route');
        if (!$route || str_starts_with((string) $route, '_')) {
            return null;
        }

        $params = $request->attributes->get('_route_params', []);
        $params['_locale'] = $locale;

        $currentLocale = $request->getLocale();

        if ('product_category' === $route && isset($params['slug'])) {
            $category = $this->categoryRepository->findOneByLocalizedSlug($params['slug'], $currentLocale);
            if (!$category) {
                return $this->urlGenerator->generate('products', ['_locale' => $locale], $referenceType);
            }
            $params['slug'] = $this->slug($category, $locale);
        }

        if ('product_detail' === $route && isset($params['slug'], $params['categorySlug'])) {
            $product = $this->productRepository->findActiveByLocalizedSlug($params['slug'], $currentLocale);
            if (!$product) {
                return $this->urlGenerator->generate('products', ['_locale' => $locale], $referenceType);
            }
            $params['slug'] = $this->slug($product, $locale);
            $params['categorySlug'] = $this->slug($product->getCategory(), $locale);
        }

        return $this->urlGenerator->generate($route, $params, $referenceType);
    }

    public function matchesLocalizedSlug(Category|Product $entity, string $slug, string $locale): bool
    {
        return $this->slug($entity, $locale) === $slug;
    }
}
