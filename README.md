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

``` php

BladeOnDemand::render('Hello {{ $name }}', ['name' => 'Protone Media']);

//

$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

BladeOnDemand::renderMarkdownMail($contents, ['name' => 'Protone Media']);

BladeOnDemand::renderMarkdownText($contents, ['name' => 'Protone Media']);

BladeOnDemand::parseMarkdownText($contents, ['name' => 'Protone Media']);

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