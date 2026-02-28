# Laravel Blade On Demand Reference

Complete reference for `protonemedia/laravel-blade-on-demand`. Full documentation: https://github.com/protonemedia/laravel-blade-on-demand#readme

## What this package does

Render **Blade template strings** (in memory) without writing view files to disk.

It also provides helpers to render Markdown mail templates similarly to Laravel’s Markdown mailables.

## Installation

```bash
composer require protonemedia/laravel-blade-on-demand
```

## Rendering a Blade template string

Use the `BladeOnDemand` facade:

```php
use ProtoneMedia\LaravelBladeOnDemand\BladeOnDemand;

$output = BladeOnDemand::render('Hello {{ $name }}', ['name' => 'Protone Media']);

// "Hello Protone Media"
```

The template string can include standard Blade features (components, directives, control structures, etc.).

### Data passing

The second argument is the data array.

Common pattern:

```php
BladeOnDemand::render(
    '<p>User: {{ $user->name }}</p>',
    ['user' => $user]
);
```

## Handling missing variables

Enable “fill missing variables” mode to prevent rendering from failing when variables are missing.

Default behavior: if `$name` is missing, it will be filled with the variable name (e.g. `'name'`).

```php
$output = BladeOnDemand::fillMissingVariables()
    ->render('Hello {{ $name }}', []);

// "Hello name"
```

### Custom missing-variable handler

Provide a callable to customize what value should be substituted:

```php
$output = BladeOnDemand::fillMissingVariables(
    fn (string $variable) => "_MISSING_{$variable}_MISSING_"
)->render('Hello {{ $name }}');

// "Hello _MISSING_name_MISSING_"
```

#### Pitfalls

- This is intended for previewing templates. Blade control structures may still behave unexpectedly if inputs are “made up”.
- If you’re rendering untrusted templates, treat them as code (they can execute Blade/PHP). This package does not sandbox Blade.

## Rendering Markdown mail

These helpers are useful when you want the output of Laravel’s Markdown mail components without creating a full Mailable.

### Render Markdown mail to HTML

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$html = BladeOnDemand::renderMarkdownMailToHtml($contents, ['name' => 'Protone Media']);
```

### Specify a mail theme

Same concept as `$mailable->theme('...')`:

```php
BladeOnDemand::theme('invoice')
    ->renderMarkdownMailToHtml($contents, $data);
```

### Render Markdown mail to plain text

Uses the `text` mail component directory:

```php
$text = BladeOnDemand::renderMarkdownMailToText($contents, ['name' => 'Protone Media']);
```

### Parse Markdown mail (render + parse)

`parseMarkdownMail()` renders the Blade and then parses Markdown into HTML:

```php
$parsed = BladeOnDemand::parseMarkdownMail($contents, ['name' => 'Protone Media']);
```

## Common patterns

- **Template previews/editors:** store a template string in DB and render it for preview.
- **Notification previews:** generate the HTML/text for mail previews.
- **One-off rendering:** for short templates where creating a view file is overkill.
