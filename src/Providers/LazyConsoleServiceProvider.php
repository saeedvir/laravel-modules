<?php

namespace Saeedvir\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class LazyConsoleServiceProvider extends ServiceProvider
{
    /**
     * Essential commands that should always be loaded
     */
    protected array $essentialCommands = [
        'module:make',
        'module:list',
        'module:enable',
        'module:disable',
        'module:use',
        'module:unuse',
    ];

    /**
     * Command groups for lazy loading
     */
    protected array $commandGroups = [
        'make' => [
            'module:make-controller',
            'module:make-model',
            'module:make-migration',
            'module:make-seeder',
            'module:make-factory',
            'module:make-provider',
            'module:make-middleware',
            'module:make-mail',
            'module:make-notification',
            'module:make-listener',
            'module:make-request',
            'module:make-event',
            'module:make-job',
            'module:make-policy',
            'module:make-observer',
            'module:make-rule',
            'module:make-resource',
            'module:make-test',
            'module:make-component',
        ],
        'database' => [
            'module:migrate',
            'module:migrate-rollback',
            'module:migrate-refresh',
            'module:migrate-reset',
            'module:migrate-status',
            'module:seed',
        ],
        'publishing' => [
            'module:publish',
            'module:publish-migration',
            'module:publish-config',
            'module:publish-translation',
        ],
    ];

    /**
     * Command class mappings
     */
    protected array $commandClasses = [
        'module:make' => \Saeedvir\Modules\Commands\Make\ModuleMakeCommand::class,
        'module:list' => \Saeedvir\Modules\Commands\Actions\ListCommand::class,
        'module:enable' => \Saeedvir\Modules\Commands\Actions\EnableCommand::class,
        'module:disable' => \Saeedvir\Modules\Commands\Actions\DisableCommand::class,
        'module:use' => \Saeedvir\Modules\Commands\Actions\UseCommand::class,
        'module:unuse' => \Saeedvir\Modules\Commands\Actions\UnUseCommand::class,
        'module:make-controller' => \Saeedvir\Modules\Commands\Make\ControllerMakeCommand::class,
        'module:make-model' => \Saeedvir\Modules\Commands\Make\ModelMakeCommand::class,
        'module:make-migration' => \Saeedvir\Modules\Commands\Make\MigrationMakeCommand::class,
        'module:make-seeder' => \Saeedvir\Modules\Commands\Make\SeedMakeCommand::class,
        'module:make-factory' => \Saeedvir\Modules\Commands\Make\FactoryMakeCommand::class,
        'module:make-provider' => \Saeedvir\Modules\Commands\Make\ProviderMakeCommand::class,
        'module:migrate' => \Saeedvir\Modules\Commands\Database\MigrateCommand::class,
        'module:migrate-rollback' => \Saeedvir\Modules\Commands\Database\MigrateRollbackCommand::class,
        'module:seed' => \Saeedvir\Modules\Commands\Database\SeedCommand::class,
    ];

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Only register essential commands immediately
        $this->registerEssentialCommands();
        
        // Set up lazy loading for other command groups
        $this->setupLazyCommandLoading();
    }

    /**
     * Register essential commands that should always be available.
     */
    protected function registerEssentialCommands(): void
    {
        foreach ($this->essentialCommands as $command) {
            if (isset($this->commandClasses[$command])) {
                $this->app->singleton($this->commandClasses[$command]);
                $this->commands($this->commandClasses[$command]);
            }
        }
    }

    /**
     * Setup lazy loading for command groups.
     */
    protected function setupLazyCommandLoading(): void
    {
        // Register event listener for when Artisan starts
        $this->app->booted(function () {
            if ($this->app->runningInConsole()) {
                $this->registerLazyCommands();
            }
        });
    }

    /**
     * Register commands lazily based on what's being executed.
     */
    protected function registerLazyCommands(): void
    {
        $argv = $_SERVER['argv'] ?? [];
        $command = $argv[1] ?? '';

        // Determine which group to load based on command
        $groupToLoad = $this->determineCommandGroup($command);
        
        if ($groupToLoad) {
            $this->loadCommandGroup($groupToLoad);
        } else {
            // If we can't determine the group, load all (fallback)
            $this->loadAllCommands();
        }
    }

    /**
     * Determine which command group should be loaded.
     */
    protected function determineCommandGroup(string $command): ?string
    {
        foreach ($this->commandGroups as $group => $commands) {
            if (in_array($command, $commands)) {
                return $group;
            }
        }

        // Check for partial matches
        if (str_contains($command, 'make')) {
            return 'make';
        }
        
        if (str_contains($command, 'migrate') || str_contains($command, 'seed')) {
            return 'database';
        }
        
        if (str_contains($command, 'publish')) {
            return 'publishing';
        }

        return null;
    }

    /**
     * Load a specific command group.
     */
    protected function loadCommandGroup(string $group): void
    {
        if (!isset($this->commandGroups[$group])) {
            return;
        }

        foreach ($this->commandGroups[$group] as $command) {
            if (isset($this->commandClasses[$command])) {
                $this->app->singleton($this->commandClasses[$command]);
                $this->commands($this->commandClasses[$command]);
            }
        }
    }

    /**
     * Load all commands (fallback method).
     */
    protected function loadAllCommands(): void
    {
        foreach ($this->commandClasses as $command => $class) {
            if (!in_array($command, $this->essentialCommands)) {
                $this->app->singleton($class);
                $this->commands($class);
            }
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return array_values($this->commandClasses);
    }
}
