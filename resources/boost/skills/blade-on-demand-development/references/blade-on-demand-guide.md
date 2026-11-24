# Laravel Blade On Demand Reference

Complete reference for `protonemedia/laravel-blade-on-demand`. Source: https://github.com/protonemedia/laravel-blade-on-demand

## Installation

```bash
composer require protonemedia/laravel-blade-on-demand
```

The package auto-discovers its service provider. No additional configuration is required.

## Rendering Blade Templates

### Basic rendering

Render any valid Blade template from a string. All standard Blade features (directives, components, expressions) are supported:

```php
use ProtoneMedia\BladeOnDemand\Facades\BladeOnDemand;

$output = BladeOnDemand::render('Hello {{ $name }}', ['name' => 'Protone Media']);
// "Hello Protone Media"
```

### Using Blade directives

```php
$template = '@if($active) Active @else Inactive @endif';
$output = BladeOnDemand::render($template, ['active' => true]);
// " Active "
```

### Using Blade components

```php
$template = '@component("mail::button", ["url" => $url]) Click Here @endcomponent';
$output = BladeOnDemand::render($template, ['url' => 'https://example.com']);
```

## Handling Missing Variables

### Fill with variable name

When `fillMissingVariables()` is called without arguments, missing variables are replaced with their own name:

```php
$output = BladeOnDemand::fillMissingVariables()->render('Hello {{ $name }}', []);
// "Hello name"
```

### Fill with custom callback

Pass a callable to customize how missing variables are replaced:

```php
$output = BladeOnDemand::fillMissingVariables(
    fn ($variable) => "_MISSING_{$variable}_MISSING_"
)->render('Hello {{ $name }}');
// "Hello _MISSING_name_MISSING_"
```

### Detect missing variables

Use `getMissingVariables()` to find which variables a template expects but are not provided:

```php
$missing = BladeOnDemand::getMissingVariables(
    'Hello {{ $name }}, welcome to {{ $app }}',
    ['name' => 'World']
);
// ['app']
```

## Markdown Mail Rendering

### Render to HTML

Render Markdown mailable content as fully styled HTML, just like a real Markdown mailable:

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToHtml($contents, ['name' => 'Protone Media']);
// Full HTML document with inline CSS styling
```

### Render to text

Render Markdown mailable content using the text mail components:

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToText($contents, ['name' => 'Protone Media']);
// Plain text output:
// [AppName](http://localhost)
//
// # Hello Protone Media
//
// © 2020 AppName. All rights reserved.
```

### Parse Markdown mail

Render to text and then parse the Markdown into HTML:

```php
$output = BladeOnDemand::parseMarkdownMail($contents, ['name' => 'Protone Media']);
// <p><a href="http://localhost">AppName</a></p>
// <h1>Hello Protone Media</h1>
// <p>© 2020 AppName. All rights reserved.</p>
```

### Custom mail theme

Apply a custom mail theme when rendering HTML emails:

```php
$output = BladeOnDemand::theme('invoice')->renderMarkdownMailToHtml($contents, $data);
```

The theme name corresponds to a Blade view in `resources/views/vendor/mail/html/themes/` or a namespaced view.

## Combining Features

### Missing variables with Markdown mail

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    'Your order **{{ $orderId }}** has been placed.',
    '@endcomponent',
]);

$output = BladeOnDemand::fillMissingVariables()
    ->renderMarkdownMailToHtml($contents, []);
```

### Using mail components

Blade On Demand supports all standard Laravel mail Markdown components:

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Order Confirmation',
    '',
    'Thank you {{ $name }}!',
    '',
    '@component("mail::button", ["url" => $actionUrl])',
    'View Order',
    '@endcomponent',
    '',
    '@component("mail::table")',
    '| Item | Price |',
    '| --- | --- |',
    '| Widget | $10.00 |',
    '@endcomponent',
    '',
    'Thanks,<br>',
    '{{ config("app.name") }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToHtml($contents, [
    'name' => 'John',
    'actionUrl' => 'https://example.com/orders/123',
]);
```

## API Reference

### `BladeOnDemand` Facade Methods

| Method | Return | Description |
| --- | --- | --- |
| `render(string $contents, array $data = [])` | `string` | Render a Blade template string |
| `fillMissingVariables(callable $callback = null)` | `$this` | Enable missing variable handling |
| `getMissingVariables(string $contents, array $data = [])` | `array` | Get list of missing variable names |
| `theme(string $theme)` | `$this` | Set the mail theme |
| `renderMarkdownMailToHtml(string $contents, array $data = [])` | `string` | Render Markdown mail as styled HTML |
| `renderMarkdownMailToText(string $contents, array $data = [])` | `string` | Render Markdown mail as plain text |
| `parseMarkdownMail(string $contents, array $data = [])` | `string` | Render to text then parse Markdown to HTML |

### Method Chaining

`fillMissingVariables()` and `theme()` are chainable and reset after each render call:

```php
// Both are applied to this render
$output = BladeOnDemand::fillMissingVariables()
    ->theme('invoice')
    ->renderMarkdownMailToHtml($contents, $data);

// Subsequent calls start fresh — no need to reset manually
$output2 = BladeOnDemand::render('{{ $foo }}', ['foo' => 'bar']);
```

## Service Provider and Facade

The package registers `BladeOnDemandRenderer` as a singleton in the service container:

```php
// Via facade (recommended)
use ProtoneMedia\BladeOnDemand\Facades\BladeOnDemand;
BladeOnDemand::render($template, $data);

// Via service container
$renderer = app('laravel-blade-on-demand');
$renderer->render($template, $data);

// Via dependency injection
use ProtoneMedia\BladeOnDemand\BladeOnDemandRenderer;

public function __construct(BladeOnDemandRenderer $renderer)
{
    $this->renderer = $renderer;
}
```
