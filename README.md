# Laravel Blade On Demand

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-blade-on-demand)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-blade-on-demand/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-blade-on-demand)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-blade-on-demand.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-blade-on-demand)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-blade-on-demand)

## Installation

You can install the package via composer:

```bash
composer require protonemedia/laravel-blade-on-demand
```

## Usage

### Render Blade template
``` php
$output = BladeOnDemand::render('Hello {{ $name }}', ['name' => 'Protone Media']);

echo $output;

// "Hello Protone Media"
```

### Render Markdown Mail to HTML

``` php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToHtml($contents, ['name' => 'Protone Media']);

echo $output->toHtml());

// <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

### Render Markdown Mail to text

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

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/pascalbaljetmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.