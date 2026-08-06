<?php

namespace App\EventSubscriber;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\LocalizationService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Redirect legacy English paths and old unprefixed URLs to localized canonical URLs.
 */
class LegacyLocaleRedirectSubscriber implements EventSubscriberInterface
{
    private const STATIC_LEGACY = [
        '/about'                => ['route' => 'about', 'locale' => 'sr'],
        '/contact'              => ['route' => 'contact', 'locale' => 'sr'],
        '/faq'                  => ['route' => 'faq', 'locale' => 'sr'],
        '/products'             => ['route' => 'products', 'locale' => 'sr'],
        '/politika-privatnosti' => ['route' => 'privacy_policy', 'locale' => 'sr'],
        '/privacy-policy'       => ['route' => 'privacy_policy', 'locale' => 'en'],
        '/sr/about'             => ['route' => 'about', 'locale' => 'sr'],
        '/sr/contact'           => ['route' => 'contact', 'locale' => 'sr'],
        '/sr/products'          => ['route' => 'products', 'locale' => 'sr'],
        '/en/o-nama'            => ['route' => 'about', 'locale' => 'en'],
        '/en/kontakt'           => ['route' => 'contact', 'locale' => 'en'],
        '/en/proizvodi'         => ['route' => 'products', 'locale' => 'en'],
        '/en/politika-privatnosti' => ['route' => 'privacy_policy', 'locale' => 'en'],
        '/sr/privacy-policy'    => ['route' => 'privacy_policy', 'locale' => 'sr'],
    ];

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private CategoryRepository $categoryRepository,
        private ProductRepository $productRepository,
        private LocalizationService $localization,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 33]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $path = $event->getRequest()->getPathInfo();

        if (isset(self::STATIC_LEGACY[$path])) {
            $target = self::STATIC_LEGACY[$path];
            $event->setResponse(new RedirectResponse(
                $this->urlGenerator->generate($target['route'], ['_locale' => $target['locale']]),
                RedirectResponse::HTTP_MOVED_PERMANENTLY,
            ));

            return;
        }

        // /products/{slug} or /sr/products/{slug} → localized category URL
        if (preg_match('#^/(?:sr/)?products/([^/]+)$#', $path, $m)) {
            $category = $this->categoryRepository->findOneByLocalizedSlug($m[1], 'sr');
            if ($category) {
                $event->setResponse(new RedirectResponse(
                    $this->urlGenerator->generate('product_category', [
                        '_locale' => 'sr',
                        'slug'    => $this->localization->slug($category, 'sr'),
                    ]),
                    RedirectResponse::HTTP_MOVED_PERMANENTLY,
                ));
            }

            return;
        }

        // /products/{cat}/{product} or /sr/products/{cat}/{product}
        if (preg_match('#^/(?:sr/)?products/([^/]+)/([^/]+)$#', $path, $m)) {
            $product = $this->productRepository->findActiveByLocalizedSlug($m[2], 'sr');
            if ($product) {
                $event->setResponse(new RedirectResponse(
                    $this->urlGenerator->generate('product_detail', [
                        '_locale'      => 'sr',
                        'categorySlug' => $this->localization->slug($product->getCategory(), 'sr'),
                        'slug'         => $this->localization->slug($product, 'sr'),
                    ]),
                    RedirectResponse::HTTP_MOVED_PERMANENTLY,
                ));
            }

            return;
        }

        // /en/proizvodi/... wrong language path segment
        if (preg_match('#^/en/proizvodi(?:/(.*))?$#', $path, $m)) {
            $rest = $m[1] ?? '';
            if ('' === $rest) {
                $event->setResponse(new RedirectResponse(
                    $this->urlGenerator->generate('products', ['_locale' => 'en']),
                    RedirectResponse::HTTP_MOVED_PERMANENTLY,
                ));

                return;
            }

            $parts = explode('/', $rest);
            if (1 === count($parts)) {
                $category = $this->categoryRepository->findOneByLocalizedSlug($parts[0], 'sr')
                    ?? $this->categoryRepository->findOneByLocalizedSlug($parts[0], 'en');
                if ($category) {
                    $event->setResponse(new RedirectResponse(
                        $this->urlGenerator->generate('product_category', [
                            '_locale' => 'en',
                            'slug'    => $this->localization->slug($category, 'en'),
                        ]),
                        RedirectResponse::HTTP_MOVED_PERMANENTLY,
                    ));
                }

                return;
            }

            if (2 === count($parts)) {
                $product = $this->productRepository->findActiveByLocalizedSlug($parts[1], 'sr')
                    ?? $this->productRepository->findActiveByLocalizedSlug($parts[1], 'en');
                if ($product) {
                    $event->setResponse(new RedirectResponse(
                        $this->urlGenerator->generate('product_detail', [
                            '_locale'      => 'en',
                            'categorySlug' => $this->localization->slug($product->getCategory(), 'en'),
                            'slug'         => $this->localization->slug($product, 'en'),
                        ]),
                        RedirectResponse::HTTP_MOVED_PERMANENTLY,
                    ));
                }
            }
        }
    }
}
