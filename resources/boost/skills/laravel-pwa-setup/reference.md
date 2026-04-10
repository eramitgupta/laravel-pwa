# Reference

## Package Surface

- Install command: `php artisan erag:install-pwa`
- Manifest update command: `php artisan erag:update-manifest`
- Blade directive: `@PwaHead`
- Blade directive: `@RegisterServiceWorkerScript`
- Facade: `EragLaravelPwa\Facades\PWA`
- Logo upload helper: `EragLaravelPwa\Core\PWA::processLogo($request)`
- Config file: `config/pwa.php`

## Installation

```bash
composer require erag/laravel-pwa
php artisan erag:install-pwa
```

Laravel 11, 12, and 13 can register the service provider in `bootstrap/providers.php` if needed:

```php
use EragLaravelPwa\EragLaravelPwaServiceProvider;

return [
    // ...
    EragLaravelPwaServiceProvider::class,
];
```

Laravel 8, 9, and 10 can register it in `config/app.php`:

```php
'providers' => [
    // ...
    EragLaravelPwa\EragLaravelPwaServiceProvider::class,
],
```

## Blade Layout Example

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    @PwaHead
    <title>{{ config('app.name') }}</title>
</head>
<body>
    {{ $slot ?? '' }}

    @RegisterServiceWorkerScript
</body>
</html>
```

## Config Example

```php
return [
    'install-button' => true,

    'manifest' => [
        'name' => 'Laravel PWA',
        'short_name' => 'LPT',
        'background_color' => '#6777ef',
        'display' => 'fullscreen',
        'description' => 'A Progressive Web Application setup for Laravel projects.',
        'theme_color' => '#6777ef',
        'icons' => [
            [
                'src' => 'logo.png',
                'sizes' => '512x512',
                'type' => 'image/png',
            ],
        ],
    ],

    'debug' => env('APP_DEBUG', false),
    'livewire-app' => false,
];
```

## Update Manifest From Config

After changing `config/pwa.php`, regenerate the published manifest:

```bash
php artisan erag:update-manifest
```

## Runtime Manifest Update

Use the facade to write a new `public/manifest.json` at runtime:

```php
use EragLaravelPwa\Facades\PWA;

PWA::update([
    'name' => 'Laravel Apps',
    'short_name' => 'LA',
    'background_color' => '#6777ef',
    'display' => 'fullscreen',
    'description' => 'A Progressive Web Application setup for Laravel projects.',
    'theme_color' => '#6777ef',
    'icons' => [
        [
            'src' => 'logo.png',
            'sizes' => '512x512',
            'type' => 'image/png',
        ],
    ],
]);
```

## Controller Example

```php
namespace App\Http\Controllers;

use EragLaravelPwa\Facades\PWA;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PwaSettingsController extends Controller
{
    public function updateManifest(Request $request): RedirectResponse
    {
        $updated = PWA::update([
            'name' => $request->string('name')->toString(),
            'short_name' => $request->string('short_name')->toString(),
            'background_color' => '#111827',
            'display' => 'standalone',
            'description' => 'Custom PWA manifest generated from admin settings.',
            'theme_color' => '#111827',
            'icons' => [
                [
                    'src' => 'logo.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                ],
            ],
        ]);

        return back()->with(
            $updated ? 'status' : 'error',
            $updated ? 'PWA manifest updated.' : 'PWA manifest update failed.'
        );
    }
}
```

## Logo Upload Example

The package validates `logo` as a PNG image with minimum dimensions `512x512` and max size `1024 KB`.

```html
<input type="file" name="logo" accept=".png">
```

```php
namespace App\Http\Controllers;

use EragLaravelPwa\Core\PWA;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function uploadLogo(Request $request): RedirectResponse
    {
        $response = PWA::processLogo($request);

        if ($response['status']) {
            return back()->with('success', $response['message']);
        }

        return back()->withErrors($response['errors'] ?? [$response['error'] ?? 'Something went wrong.']);
    }
}
```

## Livewire Example

Enable the Livewire flag in config:

```php
'livewire-app' => true,
```

Then keep the same Blade directives in your main layout:

```blade
@PwaHead
@RegisterServiceWorkerScript
```

## React Or Vue Shell Example

Use a Blade layout to host the frontend app and still output the package directives:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/js/app.js'])
    @PwaHead
</head>
<body>
    <div id="app"></div>
    @RegisterServiceWorkerScript
</body>
</html>
```

## Published Files

`php artisan erag:install-pwa` publishes:

- `config/pwa.php`
- `public/manifest.json`
- `public/offline.html`
- `public/sw.js`
- `public/logo.png`

## AI Task Examples

Use this package skill for prompts like:

- Add Laravel PWA support to my app layout.
- Add `@PwaHead` and service worker registration to my Blade template.
- Update the manifest with my app name, colors, and icon.
- Show the install button for my Laravel PWA.
- Make this Livewire app work with erag/laravel-pwa.
- Add PWA support to a Laravel app that uses Vue or React.
- Build an admin controller that updates `manifest.json`.
- Add logo upload for the PWA icon.
