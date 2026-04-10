---
name: laravel-pwa-package
description: Maintain and extend the Erag Laravel PWA package. Use when working on this package's PWA behavior, service worker registration, manifest generation, install prompt UX, package-safe Laravel changes, service/provider wiring, or package documentation alignment.
---

# Laravel PWA Package

Use this skill when modifying this package or helping consumers integrate it correctly.

## Read First

- Read `README.md` for the package's public install and usage contract.
- Read `config/pwa.php`, `resources/manifest.json`, and `resources/sw.js` before changing generated behavior.
- Inspect `src/Services/PWAService.php` and `src/EragLaravelPwaServiceProvider.php` before changing package flow.

## Workflow

1. Keep changes package-safe across supported Laravel versions `8.x` through `13.x`.
2. Preserve backward compatibility for public APIs unless a breaking change is explicitly requested.
3. Keep service classes free of console-only helpers.
4. Run `php -l` on modified PHP files.

## PWA Rules

- Register `sw.js` only through `navigator.serviceWorker.register(...)`.
- Keep iOS install support in generated head tags and install prompt behavior.
- Treat manifest output and published public assets as consumer-facing behavior.
- Avoid unnecessary visual changes to the floating install UI.

## Important Files

- `src/Services/PWAService.php`
- `src/EragLaravelPwaServiceProvider.php`
- `src/Commands/`
- `src/Core/`
- `config/pwa.php`
- `resources/manifest.json`
- `resources/sw.js`
