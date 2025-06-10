<?php

namespace EragLaravelPwa;

use EragLaravelPwa\Commands\PWACommand;
use EragLaravelPwa\Commands\PwaPublishCommand;
use EragLaravelPwa\Services\PWAService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class EragLaravelPwaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PWAService::class, function ($app) {
            return new PWAService;
        });

        $this->commands([
            PwaPublishCommand::class,
            PWACommand::class,
        ]);

        $this->publishes([
            __DIR__.'/../config/pwa.php' => config_path('pwa.php'),
        ], 'erag:publish-pwa-config');

        $this->publishes([
            __DIR__ . '/../resources/manifest.json' => public_path('manifest.json'),
        ], 'erag:publish-manifest');

        $this->publishes([
            __DIR__ . '/../resources/offline.html' => public_path('offline.html'),
        ], 'erag:publish-offline');

        $this->publishes([
            __DIR__ . '/../resources/sw.js' => public_path('sw.js'),
        ], 'erag:publish-sw');

        $this->publishes([
            __DIR__ . '/../resources/logo.png' => public_path('logo.png'),
        ], 'erag:publish-logo');

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Blade::directive('PwaHead', function () {
            return '<?php echo app(\\EragLaravelPwa\\Services\\PWAService::class)->headTag(); ?>';
        });

        Blade::directive('RegisterServiceWorkerScript', function () {
            return '<?php echo app(\\EragLaravelPwa\\Services\\PWAService::class)->registerServiceWorkerScript(); ?>';
        });

        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('PWA', \EragLaravelPwa\Facades\PWA::class);
        }
    }
}
