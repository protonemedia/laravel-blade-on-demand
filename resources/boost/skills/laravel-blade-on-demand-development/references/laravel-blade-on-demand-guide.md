# laravel-blade-on-demand development guide

For full documentation, see the README: https://github.com/protonemedia/laravel-blade-on-demand#readme

## At a glance
Compiles and renders Blade templates **in memory** (useful for dynamic rendering/PDF/email-like flows).

## Local setup
- Install dependencies: `composer install`
- Keep the dev loop package-focused (avoid adding app-only scaffolding).

## Testing
- Run: `composer test` (preferred) or the repository’s configured test runner.
- Add regression tests for bug fixes.

## Notes & conventions
- Avoid filesystem assumptions; this package's core value is in-memory compilation.
- Watch for security implications (untrusted templates/variables).
- Prefer tests around compilation caching and rendering output.
