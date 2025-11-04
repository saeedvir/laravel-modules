<?php

namespace Saeedvir\Modules;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

abstract class CachedFileRepository extends FileRepository
{
    /**
     * Cache TTL in seconds (1 hour default)
     */
    protected int $cacheTtl = 3600;

    /**
     * Cache key prefix
     */
    protected string $cachePrefix = 'laravel_modules';

    /**
     * Get & scan all modules with caching.
     */
    public function scan(): array
    {
        if ($this->app->runningUnitTests()) {
            return parent::scan();
        }

        $cacheKey = $this->getCacheKey('modules_scan');
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return parent::scan();
        });
    }

    /**
     * Find a specific module with caching.
     */
    public function find(string $name): ?Module
    {
        if ($this->app->runningUnitTests()) {
            return parent::find($name);
        }

        $cacheKey = $this->getCacheKey("module_{$name}");
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($name) {
            return parent::find($name);
        });
    }

    /**
     * Get modules by status with caching.
     */
    public function getByStatus($status): array
    {
        if ($this->app->runningUnitTests()) {
            return parent::getByStatus($status);
        }

        $statusKey = $status ? 'enabled' : 'disabled';
        $cacheKey = $this->getCacheKey("modules_by_status_{$statusKey}");
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($status) {
            return parent::getByStatus($status);
        });
    }

    /**
     * Get all enabled modules with caching.
     */
    public function allEnabled(): array
    {
        return $this->getByStatus(true);
    }

    /**
     * Get all disabled modules with caching.
     */
    public function allDisabled(): array
    {
        return $this->getByStatus(false);
    }

    /**
     * Clear all module caches.
     */
    public function clearCache(): void
    {
        $tags = [$this->cachePrefix];
        
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags($tags)->flush();
        } else {
            // Fallback for cache stores that don't support tags
            $keys = [
                'modules_scan',
                'modules_by_status_enabled',
                'modules_by_status_disabled'
            ];
            
            foreach ($keys as $key) {
                Cache::forget($this->getCacheKey($key));
            }
            
            // Clear individual module caches
            foreach ($this->all() as $module) {
                Cache::forget($this->getCacheKey("module_{$module->getName()}"));
            }
        }
        
        // Reset static cache
        parent::resetModules();
    }

    /**
     * Enable a module and clear related caches.
     */
    public function enable(string $name)
    {
        parent::enable($name);
        $this->clearModuleStatusCaches();
    }

    /**
     * Disable a module and clear related caches.
     */
    public function disable(string $name)
    {
        parent::disable($name);
        $this->clearModuleStatusCaches();
    }

    /**
     * Delete a module and clear all related caches.
     */
    public function delete(string $name): bool
    {
        $result = parent::delete($name);
        $this->clearCache();
        return $result;
    }

    /**
     * Generate cache key with prefix.
     */
    protected function getCacheKey(string $key): string
    {
        return "{$this->cachePrefix}.{$key}";
    }

    /**
     * Clear caches related to module status.
     */
    protected function clearModuleStatusCaches(): void
    {
        Cache::forget($this->getCacheKey('modules_by_status_enabled'));
        Cache::forget($this->getCacheKey('modules_by_status_disabled'));
        Cache::forget($this->getCacheKey('modules_scan'));
    }

    /**
     * Set cache TTL.
     */
    public function setCacheTtl(int $ttl): self
    {
        $this->cacheTtl = $ttl;
        return $this;
    }

    /**
     * Get modules in chunks for large-scale applications.
     */
    public function getModulesInChunks(int $chunkSize = 50): Collection
    {
        $cacheKey = $this->getCacheKey("modules_chunks_{$chunkSize}");
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($chunkSize) {
            return collect($this->all())->chunk($chunkSize);
        });
    }
}
