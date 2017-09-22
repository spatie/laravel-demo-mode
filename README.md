# A middleware to protect your work in progress from prying eyes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-demo-mode.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-demo-mode)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-demo-mode/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-demo-mode)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/5fb290e3-4f00-4abb-91f3-1163d3715108.svg?style=flat-square)](https://insight.sensiolabs.com/projects/5fb290e3-4f00-4abb-91f3-1163d3715108)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-demo-mode.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-demo-mode)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-demo-mode.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-demo-mode)

Imagine you are working on a new app. Your client wants to see the progress that you've made. However your site isn't ready for prime time yet. Sure, you could create some login functionality and display the site only to logged in users. But why bother creating users when there is a more pragmatic approach?

This package provides a route middleware to protected routes from prying eyes. All users that visit a protected route will be redirect to a configurable url (e.g. `/under-construction`). To view the content of the routes a visitor must first visit a url that grants access (e.g. `/demo`).

A word to the wise: do not use this package to restrict access to sensitive data or to protect an admin section. For those cases you should use proper authentication.

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-demo-mode
```

The `Spatie\DemoMode\DemoModeServiceProvider::class` service provider will be auto registered.

The `\Spatie\DemoMode\DemoMode::class`-middleware must be registered in the kernel:

```php
//app/Http/Kernel.php

protected $routeMiddleware = [
  ...
  'demoMode' => \Spatie\DemoMode\DemoMode::class,
];
```

Naming the route middleware `DemoMode` is just a suggestion. You can give it any name you'd like.

You must publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\DemoMode\DemoModeServiceProvider"
```

This is the content of the published config file `demo-mode.php`:

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
    'redirect_unauthorized_users_to_url' => '/under-construction',

    /**
     * After have been granted access visitors will be redirected to this url.
     */
    'redirect_authorized_users_to_url' => '/',

    /**
     * The following IP addresses will automatically have access to the app
     * without having to pass the `demoAccess` route.
     */
    'authorized_ips' => [

    ],

    /**
     * If strict mode is enabled then only the IP addresses listed in `authorized_ips` are allowed.
     * Vistors won't be able to gain access by visiting the  `demoAccess` route anymore.
     */
    'strict_mode' => false,

];
```

If you want to use the `demoAccess` route you must call the `demoAccess` route macro in your routes file.
```php
Route::demoAccess('/demo');
```
Visiting `/demo` will grant access to the pages protected by demo mode. Of course you can choose any url you'd like.

If you want to automatically authorize certain IP addresses you can add them in the `authorized_ips` array in the `demo-mode.php` config file.

To disable the `demoAccess` route and only allow access to the `authorized_ips` you can enable `strict_mode` in the `demo-mode.php` config file.

## Usage

You can protect some routes by using the `demoMode`-middleware on them.

```php
//only users who have previously visited "/demo" will be able to see these pages.

Route::group(['middleware' => 'demoMode'], function () {
    Route::get('/secret-route', function() {
        echo 'Hi!';
    });
});
```

Unless you visit the url used by the `demoAccess` route macro first or from an authorized IP address, visiting these routes will result in a redirect in to the url specified in the `redirect_unauthorized_users_to_url`-key of the config file.

An authenticated user has access to all protected routes too.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
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
