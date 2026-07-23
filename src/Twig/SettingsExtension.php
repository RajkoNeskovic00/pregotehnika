<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Service\SettingsService;
use Twig\Extension\AbstractExtension;

class SettingsExtension extends AbstractExtension
{
    private SettingsService $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setting', [$this, 'getSetting']),
        ];
    }

    public function getSetting(string $label, ?string $default = null): ?string
    {
        return $this->settings->get($label, $default);
    }
}
