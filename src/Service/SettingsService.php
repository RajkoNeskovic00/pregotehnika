<?php

namespace App\Service;

use App\Entity\Setting;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\ORM\EntityManagerInterface;

class SettingsService
{
    private array $cache = [];

    private EntityManagerInterface $em;
    private CacheItemPoolInterface $cachePool;

    public function __construct(EntityManagerInterface $em, CacheItemPoolInterface $cachePool)
    {
        $this->em = $em;
        $this->cachePool = $cachePool;
    }

    public function get(string $label, ?string $default = null): ?string
    {
        if (array_key_exists($label, $this->cache)) {
            return $this->cache[$label];
        }

        $cacheItem = $this->cachePool->getItem('setting_'.$label);

        if ($cacheItem->isHit()) {
            return $this->cache[$label] = $cacheItem->get();
        }

        $setting = $this->em->getRepository(Setting::class)->findOneBy(['label' => $label]);

        $value = !empty($setting->getValue()) ? $setting->getValue() : $default;

        $cacheItem->set($value);
        $this->cachePool->save($cacheItem);

        return $this->cache[$label] = $value;
    }

    public function clearCache(): void
    {
        $this->cache = [];
    }
}
