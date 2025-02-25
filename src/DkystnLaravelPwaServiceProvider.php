<?php

namespace Dkystn\LaravelPwa;

use Dkystn\LaravelPwa\Commands\PWACommand;
use Dkystn\LaravelPwa\Commands\PwaPublishCommand;
use Dkystn\LaravelPwa\Services\PWAService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DkystnLaravelPwaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
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
            __DIR__.'/Stubs/pwa.stub' => config_path('pwa.php'),
        ], 'Dkystn:publish-pwa-config');

        $this->publishes([
            __DIR__.'/Stubs/manifest.stub' => public_path('manifest.json'),
        ], 'Dkystn:publish-manifest');

        $this->publishes([
            __DIR__.'/Stubs/offline.stub' => public_path('offline.html'),
        ], 'Dkystn:publish-offline');

        $this->publishes([
            __DIR__.'/Stubs/sw.stub' => public_path('sw.js'),
        ], 'Dkystn:publish-sw');

        $this->publishes([
            __DIR__.'/Stubs/logo.png' => public_path('logo.png'),
        ], 'Dkystn:publish-logo');

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Blade::directive('PwaHead', function () {
            return '<?php echo app(\\Dkystn\LaravelPwa\\Services\\PWAService::class)->headTag(); ?>';
        });

        Blade::directive('RegisterServiceWorkerScript', function () {
            return '<?php echo app(\\Dkystn\LaravelPwa\\Services\\PWAService::class)->registerServiceWorkerScript(); ?>';
        });
    }
}
