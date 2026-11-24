---
name: blade-on-demand-development
description: Build and work with protonemedia/laravel-blade-on-demand features including compiling Blade templates in memory, handling missing variables, and rendering Markdown mailable content to HTML or text.
license: MIT
metadata:
  author: Protone Media
---

# Blade On Demand Development

## Overview
Use protonemedia/laravel-blade-on-demand to compile Blade templates in memory without storing them on disk. Supports rendering any Blade template from a string, handling missing variables gracefully, and rendering Markdown mail components to HTML or plain text.

## When to Activate
- Activate when working with dynamic or in-memory Blade template rendering in Laravel.
- Activate when code references `BladeOnDemand`, `BladeOnDemandRenderer`, or uses `render()`, `renderMarkdownMailToHtml()`, `renderMarkdownMailToText()`, or `parseMarkdownMail()` on the facade.
- Activate when the user wants to compile Blade templates from strings, handle missing template variables, or render Markdown mail content without a Mailable class.

## Scope
- In scope: in-memory Blade rendering, missing variable detection and filling, Markdown mail rendering to HTML/text, mail theme customization.
- Out of scope: file-based Blade views, Mailable classes, general Laravel mail sending, non-Laravel frameworks.

## Workflow
1. Identify the task (rendering a Blade string, handling missing variables, rendering a Markdown mail, etc.).
2. Read `references/blade-on-demand-guide.md` and focus on the relevant section.
3. Apply the patterns from the reference, keeping code minimal and Laravel-native.

## Core Concepts

### Basic Rendering
Render any valid Blade template from a string:

```php
use ProtoneMedia\BladeOnDemand\Facades\BladeOnDemand;

$output = BladeOnDemand::render('Hello {{ $name }}', ['name' => 'World']);
// "Hello World"
```

### Handling Missing Variables
Prevent errors when variables are missing from the data array:

```php
$output = BladeOnDemand::fillMissingVariables()->render('Hello {{ $name }}', []);
// "Hello name"

$output = BladeOnDemand::fillMissingVariables(
    fn ($variable) => "_MISSING_{$variable}_MISSING_"
)->render('Hello {{ $name }}');
// "Hello _MISSING_name_MISSING_"
```

### Rendering Markdown Mail to HTML
Render Markdown mailable content with full HTML styling:

```php
$contents = implode(PHP_EOL, [
    '@component("mail::message")',
    '# Hello {{ $name }}',
    '@endcomponent',
]);

$output = BladeOnDemand::renderMarkdownMailToHtml($contents, ['name' => 'World']);
```

### Rendering Markdown Mail to Text
Render Markdown mailable content as plain text:

```php
$output = BladeOnDemand::renderMarkdownMailToText($contents, ['name' => 'World']);
```

### Parsing Markdown Mail
Render to text and then parse the Markdown into HTML:

```php
$output = BladeOnDemand::parseMarkdownMail($contents, ['name' => 'World']);
```

## Do and Don't

Do:
- Always pass template content as the first argument and data as the second argument to `render()`.
- Use `fillMissingVariables()` when previewing templates without complete data.
- Use `renderMarkdownMailToHtml()` for generating styled HTML email previews.
- Chain `theme()` before `renderMarkdownMailToHtml()` to apply a custom mail theme.
- Use `getMissingVariables()` to detect which variables a template expects.

Don't:
- Don't forget to install the package via `composer require protonemedia/laravel-blade-on-demand`.
- Don't use `renderMarkdownMailToHtml()` for plain Blade templates — use `render()` instead.
- Don't expect `fillMissingVariables()` to handle Blade statements like `@if` or `@foreach` reliably — it only fills `$variable` references.
- Don't call `render()` without data when the template uses variables — either pass the data or use `fillMissingVariables()`.

## References
- `references/blade-on-demand-guide.md`
