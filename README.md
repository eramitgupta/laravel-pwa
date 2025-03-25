# PWA Easy Setup for Laravel (With Vue.js and React.js)

<center>
    <img width="956" alt="Screenshot 2024-10-04 at 10 34 23 PM" src="https://github.com/user-attachments/assets/2b187de0-d5cc-4871-9c5d-ce0ffbb5a26c">
</center>
<div align="center">

[![Packagist License](https://img.shields.io/badge/Licence-MIT-blue)](https://github.com/eramitgupta/laravel-pwa/blob/main/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/erag/laravel-pwa?label=Stable)](https://packagist.org/packages/erag/laravel-pwa)
[![Total Downloads](https://img.shields.io/packagist/dt/erag/laravel-pwa.svg?label=Downloads)](https://packagist.org/packages/erag/laravel-pwa)

</div>

Laravel PWA is a package designed to seamlessly integrate Progressive Web Application (PWA) functionality into your Laravel projects. With this package, you can easily configure, update the manifest, and register service workers, enabling any Laravel app to function as a PWA.

## Features 🚀

- Automatically generate PWA manifest and service worker
- Configurable installation button
- Supports Laravel 8, 9, 10, 11 And 12 
- Easy setup and usage
- Compatible with mobile and desktop devices

## Important ⚠️

Note: PWAs require a secure HTTPS connection to work correctly. Ensure your application is hosted with HTTPS; otherwise, service workers and other PWA features will not function as expected.


## Installation 📦

To get started, install the package using Composer:

```bash
composer require erag/laravel-pwa
```

Once installed, publish the PWA configuration files using:

```bash
php artisan erag:install-pwa
```

This will create the required configuration file `config/pwa.php` and set up PWA functionality for your application.

## Configuration ⚙️

### Main Configuration File: `config/pwa.php`

This is your main configuration file where you can customize the PWA settings.

```php
return [
    'install-button' => true, // Show or hide the install button globally.

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

    'debug' => env('APP_DEBUG', false), // Show or hide console.log in the browser globally.
];
```

### Customizing Manifest File

After changing `config/pwa.php` in your `manifest` array, run this command
You can update your PWA manifest file by running:

```bash
php artisan erag:update-manifest
```

This command updates the `manifest.json` file located in the public directory of your Laravel project.

## Usage 🛠️

To integrate PWA functionality into your layouts, use the provided Blade directives.

### 1. **Add Meta Tags**

Place the `@PwaHead` directive inside the `<head>` tag of your main layout file:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    @PwaHead <!-- Add this directive to include the PWA meta tags -->
    <title>Your App Title</title>
</head>
<body>
```

### 2. **Register Service Worker**

Just before the closing `</body>` tag in your main layout file, add:

```blade
    @RegisterServiceWorkerScript <!-- This registers the service worker -->
</body>
</html>
```

These directives will automatically generate the necessary tags and JavaScript for the PWA.

## Screenshots 📸

<img width="1470" alt="Screenshot 2024-09-19 at 10 11 01 PM" src="https://github.com/user-attachments/assets/27c08862-0557-4fbd-bd8f-90b9d05f67b3">

### Installing PWA App

<img width="1470" alt="Screenshot 2024-09-19 at 10 13 23 PM" src="https://github.com/user-attachments/assets/5e58a596-3267-42d9-98d5-c48b0f54d3ed">

### Offline Page

<img width="1470" alt="Screenshot 2024-09-19 at 10 13 52 PM" src="https://github.com/user-attachments/assets/1a80465e-0307-43ac-a1bc-9bca2cf16f8d">

## Contribution 🧑‍💻

We appreciate your interest in contributing to this Laravel PWA project! Whether you're reporting issues, fixing bugs, or adding new features, your help is greatly appreciated.

### Forking and Cloning the Repository

1. Go to the repository page on GitHub.
2. Click the **Fork** button at the top-right corner of the repository page.
3. Clone your forked repository:

   ```bash
   git clone https://github.com/your-username/laravel-pwa.git
   ```

### Reporting Issues

If you encounter any issues, please check if the issue already exists in the **Issues** section. If not, create a new issue with the following details:
- Steps to reproduce the issue
- Expected and actual behavior
- Laravel version
- Any relevant logs or screenshots

### Submit a Pull Request

When you're ready to contribute, open a pull request describing the changes you’ve made and how they improve the project. Please ensure:
- All commits are squashed into one clean commit.
- The code follows **PSR-12** standards.
- You’ve tested the changes locally.

### Coding Standards

- Follow the [PSR-12](https://www.php-fig.org/psr/psr-12/) PHP coding standards.
- Keep your commit history clean and meaningful.
- Add comments where needed but avoid over-commenting.

## Example Workflow 🌟

Here’s a simple example of how to use this package:

1. Install the package via Composer.
2. Publish the configuration files.
3. Add the `@PwaHead` directive in your layout file’s `<head>`.
4. Add the `@RegisterServiceWorkerScript` directive before the closing `</body>` tag.
5. Customize the `config/pwa.php` to fit your project’s needs.
6. Run `php artisan erag:pwa-update-manifest` to update the manifest file.
7. That's it! Your Laravel app is now PWA-enabled. 🚀
