# Laravel Blade On Demand

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-blade-on-demand)
[![Build Status](https://img.shields.io/travis/pascalbaljetmedia/laravel-blade-on-demand/master.svg?style=flat-square)](https://travis-ci.org/pascalbaljetmedia/laravel-blade-on-demand)
[![Quality Score](https://img.shields.io/scrutinizer/g/pascalbaljetmedia/laravel-blade-on-demand.svg?style=flat-square)](https://scrutinizer-ci.com/g/pascalbaljetmedia/laravel-blade-on-demand)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/laravel-blade-on-demand.svg?style=flat-square)](https://packagist.org/packages/protonemedia/laravel-blade-on-demand)

Laravel package to compile Blade templates in memory. Requires PHP 7.2 or higher, compatible with Laravel 6 and 7.

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

Similair feature as the above `renderMarkdownMailToHtml` except it uses components from the `text` directory as described in the [Laravel documentation](https://laravel.com/docs/7.x/mail#customizing-the-components).

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

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/pascalbaljetmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
