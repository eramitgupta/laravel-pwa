## Erag Laravel PWA

This package adds Progressive Web App support to Laravel applications through generated head tags, service worker registration markup, manifest publishing, offline fallback assets, and install prompt behavior.

### Package Structure

- Core package logic lives in `src/`.
- Published defaults live in `config/pwa.php` and `resources/`.
- Public-facing package behavior should stay aligned with `README.md`.

### Key Services

- `EragLaravelPwa\Services\PWAService` generates PWA head tags and service worker registration output.
- `EragLaravelPwa\EragLaravelPwaServiceProvider` registers bindings, commands, Blade directives, and published assets.
- `EragLaravelPwa\Core\LogoManager` handles uploaded logo replacement for `public/logo.png`.

### PWA Rules

- Register `sw.js` only through `navigator.serviceWorker.register(...)`.
- Keep iOS install support in generated head tags and install prompt behavior.
- Preserve manifest compatibility and published asset paths.
- Avoid unnecessary visual changes to the floating install button.

### Service Rules

- Keep console output inside command classes, not service classes.
- Keep `HeadTag()` responsible for manifest, icon, theme-color, and iOS-related meta tags.
- Keep `RegisterServiceWorkerScript()` responsible for install prompt markup and service worker registration logic.
- Keep manifest update methods safe for direct package-consumer usage.
