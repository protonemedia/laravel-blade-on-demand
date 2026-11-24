{{-- Laravel Blade On Demand Guidelines for AI Code Assistants --}}
{{-- Source: https://github.com/protonemedia/laravel-blade-on-demand --}}
{{-- License: MIT | (c) Protone Media --}}

## Blade On Demand

- `protonemedia/laravel-blade-on-demand` compiles Blade templates in memory without storing them on disk, with support for missing variable handling and Markdown mail rendering.
- Always activate the `blade-on-demand-development` skill when working with in-memory Blade rendering, dynamic template compilation, missing variable handling, or any code that uses the `BladeOnDemand` facade or `BladeOnDemandRenderer` class.
