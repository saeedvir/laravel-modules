# Laravel-Modules

[![Latest Version on Packagist](https://img.shields.io/packagist/v/saeedvir/laravel-modules.svg?style=flat-square)](https://packagist.org/packages/saeedvir/laravel-modules)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/saeedvir/laravel-modules.svg?maxAge=86400&style=flat-square)](https://scrutinizer-ci.com/g/saeedvir/laravel-modules/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/saeedvir/laravel-modules.svg?style=flat-square)](https://packagist.org/packages/saeedvir/laravel-modules)

| **Laravel** | **laravel-modules** |
|-------------|---------------------|
| 5.4         | ^1.0                |
| 5.5         | ^2.0                |
| 5.6         | ^3.0                |
| 5.7         | ^4.0                |
| 5.8         | ^5.0                |
| 6.0         | ^6.0                |
| 7.0         | ^7.0                |
| 8.0         | ^8.0                |
| 9.0         | ^9.0                |
| 10.0        | ^10.0               |
| 11.0        | ^11.0               |
| 12.0        | ^12.0               |

`saeedvir/laravel-modules` is a Laravel package created to manage your large Laravel app using modules. A Module is like a Laravel package, it has some views, controllers or models. This package is supported and tested in Laravel 12.

This package is a re-published, re-organised and maintained version of [nwidart/laravel-modules](https://github.com/nwidart/laravel-modules), optimized and slimmed for better performance.

## 🚀 What's Different?

This fork includes several optimizations:
- **40% smaller package size** - Removed rarely-used generator commands
- **Faster boot time** - Lazy loading enabled by default
- **Better performance** - Optimized module discovery with caching
- **Laravel-focused** - Lumen support removed for cleaner codebase

See [SLIMMING_REPORT.md](SLIMMING_REPORT.md) for details on optimizations made.

## Sponsors

For those who are interested in becoming a sponsor, please visit Laravel Modules Sponsor page at **[laravelmodules.com/become-a-sponsor](https://laravelmodules.com/become-a-sponsor)**.

See the website traffic at https://app.usefathom.com/share/sdinlflk/laravel+modules

### Sponsors

<a href="https://nativephp.com/mobile"><img src="https://laravelmodules.com/images/sponsors/NativePHP-mobile-light.svg" alt="NativePHP for Mobile" title="NativePHP for Mobile" width="200"/></a>

<a href="https://dcblog.dev"><img src="https://laravelmodules.com/images/sponsors/dcblog.png" alt="David Carr" title="David Carr" width="200"/></a>

## upgrade
To upgrade to version V12 follow [Upgrade Guide](https://laravelmodules.com/docs/12/getting-started/upgrade) on official document.

## Install

To install via Composer, run:

``` bash
composer require saeedvir/laravel-modules
```

The package will automatically register a service provider and alias.

Optionally, publish the package's configuration file by running:

``` bash
php artisan vendor:publish --provider="Saeedvir\Modules\LaravelModulesServiceProvider"
```

### Autoloading

By default, the module classes are not loaded automatically. You can autoload your modules by adding merge-plugin to the extra section:

```json
"extra": {
    "laravel": {
        "dont-discover": []
    },
    "merge-plugin": {
        "include": [
            "Modules/*/composer.json"
        ]
    }
},
```

**Tip: don't forget to run `composer dump-autoload` afterwards.**

## Documentation

You'll find installation instructions and full documentation on [https://laravelmodules.com/](https://laravelmodules.com/docs).

## Demo

You can see a demo using Laravel Breeze at https://github.com/laravel-modules-com/breeze-demo

This is a complete application using Auth, Base and Profile modules.

## Community

We also have a Discord community. [https://discord.gg/hkF7BRvRZK](https://discord.gg/hkF7BRvRZK) For quick help, ask questions in the appropriate channel.

## Credits

- [Saeed Vir](https://github.com/saeedvir)
- [David Carr](https://github.com/dcblogdev)
- [gravitano](https://github.com/gravitano)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
