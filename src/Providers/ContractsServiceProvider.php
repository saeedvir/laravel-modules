<?php

namespace Saeedvir\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Saeedvir\Modules\Contracts\RepositoryInterface;
use Saeedvir\Modules\Laravel\LaravelFileRepository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
    }
}
