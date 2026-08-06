<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Service\LocalizationService;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocalizationExtension extends AbstractExtension
{
    public function __construct(
        private LocalizationService $localization,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('localized', [$this, 'localized']),
            new TwigFunction('localized_slug', [$this, 'localizedSlug']),
            new TwigFunction('locale_url', [$this, 'localeUrl']),
            new TwigFunction('locale_path', [$this, 'localePath']),
        ];
    }

    public function localized(object $entity, string $field, ?string $locale = null): ?string
    {
        return $this->localization->field($entity, $field, $locale);
    }

    public function localizedSlug(object $entity, ?string $locale = null): ?string
    {
        return $this->localization->slug($entity, $locale);
    }

    public function localeUrl(string $locale): ?string
    {
        return $this->localization->alternateUrl($locale, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function localePath(string $locale): ?string
    {
        return $this->localization->alternateUrl($locale, UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}
