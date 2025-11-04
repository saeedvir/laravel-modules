<?php

namespace Saeedvir\Modules\Laravel;

use Illuminate\Container\Container;
use Saeedvir\Modules\CachedFileRepository;

class LaravelFileRepository extends CachedFileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createModule(Container $app, string $name, string $path): Module
    {
        return new Module($app, $name, $path);
    }
}
