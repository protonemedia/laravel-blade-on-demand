---
name: laravel-blade-on-demand-development
description: Build and work with protonemedia/laravel-blade-on-demand features including rendering Blade template strings in memory, handling missing variables, and rendering Markdown mail to HTML or plain text.
license: MIT
metadata:
  author: ProtoneMedia
---

# Laravel Blade On Demand Development

## Overview
Use protonemedia/laravel-blade-on-demand to render Blade template strings in memory without writing view files to disk. Supports variable data binding, missing-variable handling, and Markdown mail rendering with themes.

## When to Activate
- Activate when working with in-memory Blade rendering, template previews, or Markdown mail generation in Laravel.
- Activate when code references the `BladeOnDemand` facade, `fillMissingVariables`, `renderMarkdownMailToHtml`, or related methods.
- Activate when the user wants to render Blade strings, preview mail templates, or handle missing template variables.

## Scope
- In scope: rendering Blade strings, data passing, missing-variable handling, Markdown mail rendering, theme selection, common integration patterns.
- Out of scope: modifying this package's internal source code unless the user explicitly says they are contributing to the package.

## Workflow
1. Identify the task (rendering a template string, previewing mail, handling missing variables, etc.).
2. Read `references/laravel-blade-on-demand-guide.md` and focus on the relevant section.
3. Apply the patterns from the reference, keeping code minimal and Laravel-native.

## Core Concepts

### Rendering a Blade String
```php
use ProtoneMedia\LaravelBladeOnDemand\BladeOnDemand;

$output = BladeOnDemand::render('Hello {{ $name }}', ['name' => 'World']);
```

### Handling Missing Variables
```php
$output = BladeOnDemand::fillMissingVariables()
    ->render('Hello {{ $name }}', []);
```

### Rendering Markdown Mail to HTML
```php
$html = BladeOnDemand::renderMarkdownMailToHtml($contents, ['name' => 'Protone Media']);
```

### Rendering Markdown Mail to Plain Text
```php
$text = BladeOnDemand::renderMarkdownMailToText($contents, ['name' => 'Protone Media']);
```

## Do and Don't

Do:
- Always pass data as the second argument array to `render()`.
- Use `fillMissingVariables()` for template previews where not all variables are available.
- Use `renderMarkdownMailToHtml()` and `renderMarkdownMailToText()` for mail previews instead of creating full Mailable classes.
- Use `->theme('name')` to apply a custom mail theme before rendering.

Don't:
- Don't render untrusted user-supplied template strings without understanding that Blade can execute arbitrary PHP.
- Don't forget to call `->render()` after `fillMissingVariables()` — the method returns a builder, not the output.
- Don't invent undocumented methods/options; stick to the docs and reference.

## References
- `references/laravel-blade-on-demand-guide.md`
