# Laravel Blade On Demand

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-blade-on-demand)
![run-tests](https://github.com/protonemedia/laravel-blade-on-demand/workflows/run-tests/badge.svg)
[![Quality Score](https://img.shields.io/scrutinizer/g/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://scrutinizer-ci.com/g/protonemedia/laravel-blade-on-demand)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-blade-on-demand)

Laravel package to compile Blade templates in memory. Requires PHP 8.0 or higher, compatible with Laravel 9.

## Sponsor Us

[<img src="https://inertiaui.com/visit-card.jpg" />](https://inertiaui.com/inertia-table?utm_source=github&utm_campaign=laravel-blade-on-demand)

❤️ We proudly support the community by developing Laravel packages and giving them away for free. If this package saves you time or if you're relying on it professionally, please consider [sponsoring the maintenance and development](https://github.com/sponsors/pascalbaljet) and check out our latest premium package: [Inertia Table](https://inertiaui.com/inertia-table?utm_source=github&utm_campaign=laravel-blade-on-demand). Keeping track of issues and pull requests takes time, but we're happy to help!

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-blade-on-demand
```

## Usage

### Render Blade template

You can render any valid [Blade Template](https://laravel.com/docs/7.x/blade) by calling the `render` method on the `BladeOnDemand` facade. The method only takes two parameters, the template content and the data you want to pass to the template.

``` php
$output = BladeOnDemand::render('Hello {{ $name }}', ['name' => 'Protone Media']);

echo $output;

// "Hello Protone Media"
```

This is a just an example but you can use statements, components and other Blade features as well.

### Handle missing variables

This feature prevents your render from failing whenever a variable is missing in your data array. By default it will fill the missing variable with the name of the variable itself. In this case `$name` is missing so the data array becomes `['name' => 'name']`;

``` php
$output = BladeOnDemand::fillMissingVariables()->render('Hello {{ $name }}', []);

echo $output;

// "Hello name"
```

You could also use this feature to preview a template without any data. Note that this might give unexpected results when using statements. You can also pass a `callable` to the `fillMissingVariables` method to customize the handling of missing variables:

``` php
$output = BladeOnDemand::fillMissingVariables(
    fn ($variable) => "_MISSING_{$variable}_MISSING_"
)->render('Hello {{ $name }}');

echo $output;

// "Hello _MISSING_name_MISSING_"
```

### Render Markdown Mail to HTML

This feature can be used to render a mail as if you're using a [Markdown mailable](https://laravel.com/docs/7.x/mail#writing-markdown-messages).

``` php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToHtml($contents, ['name' => 'Protone Media']);

echo $output;

// <!DOCTYPE>
// <html xmlns="http://www.w3.org/1999/xhtml">
// <head>
//     ...
// </head>
// <body>
// <style>
//     ...
// </style>

// <table>
//     ...
//     <h1>Hello Protone Media</h1>
//     ...
// </table>
// </body>
// </html>
```

You can optionally specify a theme, just like calling the `theme` method [on a Mailable](https://laravel.com/docs/7.x/notifications#customizing-the-components).

```php
BladeOnDemand::theme('invoice')->renderMarkdownMailToHtml($contents, $data);
```

### Render Markdown Mail to text

Similair feature as the above `renderMarkdownMailToHtml` method except it uses components from the `text` directory. You can read more about this feature in the [Laravel documentation](https://laravel.com/docs/7.x/mail#customizing-the-components).

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToText($contents, ['name' => 'Protone Media']);

echo $output;

// [AppName](http://localhost)
//
// # Hello Protone Media
//
// © 2020 AppName. All rights reserved.
```

### Parse Maildown Mail

The `parseMarkdownMail` method is the same as the `renderMarkdownMailToText` method but it also parses the Markdown.

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::parseMarkdownMail($contents, ['name' => 'Protone Media']);

echo $output;

// <p><a href="http://localhost">AppName</a></p>
// <h1>Hello Protone Media</h1>
// <p>© 2020 AppName. All rights reserved.</p>
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Other Laravel packages

* [`Inertia Table`](https://inertiaui.com/inertia-table?utm_source=github&utm_campaign=laravel-blade-on-demand): The Ultimate Table for Inertia.js with built-in Query Builder.
* [`Laravel Cross Eloquent Search`](https://github.com/protonemedia/laravel-cross-eloquent-search): Laravel package to search through multiple Eloquent models.
* [`Laravel Eloquent Scope as Select`](https://github.com/protonemedia/laravel-eloquent-scope-as-select): Stop duplicating your Eloquent query scopes and constraints in PHP. This package lets you re-use your query scopes and constraints by adding them as a subquery.
* [`Laravel FFMpeg`](https://github.com/protonemedia/laravel-ffmpeg): This package provides an integration with FFmpeg for Laravel. The storage of the files is handled by Laravel's Filesystem.
* [`Laravel MinIO Testing Tools`](https://github.com/protonemedia/laravel-minio-testing-tools): Run your tests against a MinIO S3 server.
* [`Laravel Mixins`](https://github.com/protonemedia/laravel-mixins): A collection of Laravel goodies.
* [`Laravel Paddle`](https://github.com/protonemedia/laravel-paddle): Paddle.com API integration for Laravel with support for webhooks/events.
* [`Laravel Task Runner`](https://github.com/protonemedia/laravel-task-runner): Write Shell scripts like Blade Components and run them locally or on a remote server.
* [`Laravel Verify New Email`](https://github.com/protonemedia/laravel-verify-new-email): This package adds support for verifying new email addresses: when a user updates its email address, it won't replace the old one until the new one is verified.
* [`Laravel XSS Protection`](https://github.com/protonemedia/laravel-xss-protection): Laravel Middleware to protect your app against Cross-site scripting (XSS). It sanitizes request input, and it can sanatize Blade echo statements.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/protonemedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
