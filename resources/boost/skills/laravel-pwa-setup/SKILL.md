---
name: laravel-pwa-setup
description: Build and work with Erag Laravel Pwa installation, manifest updates, Blade directives, service worker registration, install prompt configuration, runtime manifest updates, and logo upload handling. Use when adding or updating PWA support in Laravel Blade, Livewire, Vue, or React applications with this package.
---

# Laravel PWA Setup

Use this skill when a task involves this package's install command, manifest update command, Blade directives, facade usage, config, or PWA logo upload flow.

## Read First

Read `reference.md` in this folder before making changes. It contains the package API, implementation notes, and package-native examples.

## Working Rules

- Use `php artisan erag:install-pwa` for initial package publishing.
- Use `php artisan erag:update-manifest` after changing `config/pwa.php` manifest values.
- Put `@PwaHead` inside the layout `<head>`.
- Put `@RegisterServiceWorkerScript` before the closing `</body>`.
- Use `EragLaravelPwa\Facades\PWA` for runtime manifest writes.
- Use `EragLaravelPwa\Core\PWA::processLogo($request)` for PNG logo upload handling.
- Keep examples aligned with `config/pwa.php`, `public/manifest.json`, `public/sw.js`, `public/offline.html`, and `public/logo.png`.
- Mention HTTPS requirements whenever the task involves service workers or installability.

## Implementation Notes

- The package publishes `config/pwa.php`, `manifest.json`, `offline.html`, `sw.js`, and `logo.png`.
- The Blade directives are `@PwaHead` and `@RegisterServiceWorkerScript`.
- The install button is controlled by `config('pwa.install-button')`.
- Livewire-specific script output is controlled by `config('pwa.livewire-app')`.
- Runtime manifest updates write to `public/manifest.json`.

## Output Expectations

- Show package-native examples first.
- Keep snippets idiomatic for Laravel applications.
- When documenting setup, use the exact Artisan commands exposed by this package.
