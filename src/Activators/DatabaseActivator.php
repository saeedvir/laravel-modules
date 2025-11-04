<?php

namespace Saeedvir\Modules\Activators;

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Schema;
use Saeedvir\Modules\Contracts\ActivatorInterface;
use Saeedvir\Modules\Module;

class DatabaseActivator implements ActivatorInterface
{
    /**
     * Laravel application instance
     */
    protected Container $app;

    /**
     * Database manager instance
     */
    protected DatabaseManager $db;

    /**
     * Cache manager instance
     */
    protected CacheManager $cache;

    /**
     * Config repository instance
     */
    protected Config $config;

    /**
     * Cache key for module statuses
     */
    protected string $cacheKey = 'modules.statuses';

    /**
     * Cache TTL in seconds
     */
    protected int $cacheTtl = 3600;

    /**
     * Database table name
     */
    protected string $table = 'module_statuses';

    /**
     * Create a new database activator instance.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->db = $app['db'];
        $this->cache = $app['cache'];
        $this->config = $app['config'];
        
        $this->table = $this->config->get('modules.activators.database.table', 'module_statuses');
        $this->cacheTtl = $this->config->get('modules.activators.database.cache_ttl', 3600);
        
        $this->ensureTableExists();
    }

    /**
     * Enables a module
     */
    public function enable(Module $module): void
    {
        $this->setActiveByName($module->getName(), true);
    }

    /**
     * Disables a module
     */
    public function disable(Module $module): void
    {
        $this->setActiveByName($module->getName(), false);
    }

    /**
     * Determine whether the given status same with a module status.
     */
    public function hasStatus(Module $module, bool $status): bool
    {
        if (!$module instanceof Module) {
            return $this->hasStatusByName($module, $status);
        }

        return $this->hasStatusByName($module->getName(), $status);
    }

    /**
     * Set active state for a module by name.
     */
    public function setActive(Module $module, bool $active): void
    {
        $this->setActiveByName($module->getName(), $active);
    }

    /**
     * Sets a module status by its name
     */
    public function setActiveByName(string $name, bool $status): void
    {
        $this->db->table($this->table)->updateOrInsert(
            ['name' => $name],
            [
                'name' => $name,
                'status' => $status,
                'updated_at' => now(),
            ]
        );

        $this->flushCache();
    }

    /**
     * Checks a module status by its name
     */
    public function hasStatusByName(string $name, bool $status): bool
    {
        $statuses = $this->getModuleStatuses();
        
        return isset($statuses[$name]) && $statuses[$name] === $status;
    }

    /**
     * Delete module from the active modules file.
     */
    public function delete(Module $module): void
    {
        $this->db->table($this->table)
            ->where('name', $module->getName())
            ->delete();

        $this->flushCache();
    }

    /**
     * Deletes any module activation statuses created by this class.
     */
    public function reset(): void
    {
        $this->db->table($this->table)->truncate();
        $this->flushCache();
    }

    /**
     * Get all module statuses from cache or database.
     */
    protected function getModuleStatuses(): array
    {
        return $this->cache->remember($this->cacheKey, $this->cacheTtl, function () {
            return $this->db->table($this->table)
                ->pluck('status', 'name')
                ->map(function ($status) {
                    return (bool) $status;
                })
                ->toArray();
        });
    }

    /**
     * Flush the module statuses cache.
     */
    protected function flushCache(): void
    {
        $this->cache->forget($this->cacheKey);
    }

    /**
     * Ensure the module statuses table exists.
     */
    protected function ensureTableExists(): void
    {
        if (!Schema::hasTable($this->table)) {
            Schema::create($this->table, function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('status')->default(true);
                $table->timestamps();
                
                $table->index(['name', 'status']);
            });
        }
    }

    /**
     * Get all enabled modules.
     */
    public function getEnabledModules(): array
    {
        $statuses = $this->getModuleStatuses();
        
        return array_keys(array_filter($statuses, function ($status) {
            return $status === true;
        }));
    }

    /**
     * Get all disabled modules.
     */
    public function getDisabledModules(): array
    {
        $statuses = $this->getModuleStatuses();
        
        return array_keys(array_filter($statuses, function ($status) {
            return $status === false;
        }));
    }

    /**
     * Bulk enable multiple modules.
     */
    public function bulkEnable(array $moduleNames): void
    {
        $data = [];
        $now = now();
        
        foreach ($moduleNames as $name) {
            $data[] = [
                'name' => $name,
                'status' => true,
                'updated_at' => $now,
            ];
        }

        $this->db->table($this->table)->upsert(
            $data,
            ['name'],
            ['status', 'updated_at']
        );

        $this->flushCache();
    }

    /**
     * Bulk disable multiple modules.
     */
    public function bulkDisable(array $moduleNames): void
    {
        $data = [];
        $now = now();
        
        foreach ($moduleNames as $name) {
            $data[] = [
                'name' => $name,
                'status' => false,
                'updated_at' => $now,
            ];
        }

        $this->db->table($this->table)->upsert(
            $data,
            ['name'],
            ['status', 'updated_at']
        );

        $this->flushCache();
    }
}
