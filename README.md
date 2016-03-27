## Work in progress do not use (yet)

# A middleware to protect your work in progress from prying eyes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-demo-mode.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-demo-mode)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-demo-mode/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-demo-mode)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/5fb290e3-4f00-4abb-91f3-1163d3715108.svg?style=flat-square)](https://insight.sensiolabs.com/projects/5fb290e3-4f00-4abb-91f3-1163d3715108)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-demo-mode.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-demo-mode)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-demo-mode.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-demo-mode)

This package provides functionality in demo mode. All unauthorized users will be redirected to a configured url. Users can be authorized by visiting an url.

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Installation

You can install the package via composer:

``` bash
$ composer require spatie/laravel-demo-mode
```

Next up, the service provider must be registered:

```php
'providers' => [
    ...
    Spatie\DemoMode\DemoServiceProvider::class,

];
```

You must publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\DemoMode\DemoModeServiceProvider"
```

This is the content of the published file laravel-demo-mode.php:

```php
return [
    
    /**
     * This is the master switch to enable demo mode.
     */
    'enabled' => env('DEMO_MODE_ENABLED', true),
    
    /**
     * Visitors that go an url that is protected by demo mode will be redirected.
     * to this url
     */
    'redirect_unauthorized_users_to_url' => '/work-in-progress',

    /**
     * Visiting this url will grant access to the pages protected by demo mode.
     */
    'grant_access_to_demo_url' => '/demo',

    /**
     * After have been granted access visitors will be redirected to this url.
     */
    'redirect_authorized_users_to_url' => '/',

];
```

## Usage

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
