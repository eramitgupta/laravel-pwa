<?php

namespace DkystnLaravelPwa\Commands;

use Illuminate\Console\Command;

class PwaPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Dkystn:publish-laravel-pwa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Service Worker Manifest File for a Laravel PWA Application';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Step 1: Publish the pwa-config
        $this->call('vendor:publish', [
            '--tag' => 'Dkystn:publish-pwa-config',
            '--force' => true,
        ]);
        $this->info('manifest.json file is published ✔');

        // Step 2: Publish the manifest
        $this->call('vendor:publish', [
            '--tag' => 'Dkystn:publish-manifest',
            '--force' => true,
        ]);
        $this->info('manifest.json file is published ✔');

        // Step 3: Publish the offline page
        $this->call('vendor:publish', [
            '--tag' => 'Dkystn:publish-offline',
            '--force' => true,
        ]);
        $this->info('offline.html file is published ✔');

        // Step 4: Publish the sw js
        $this->call('vendor:publish', [
            '--tag' => 'Dkystn:publish-sw',
            '--force' => true,
        ]);
        $this->info('sw.js file is published ✔');

        // Step 5: Publish the logo
        $this->call('vendor:publish', [
            '--tag' => 'Dkystn:publish-logo',
            '--force' => true,
        ]);
        $this->info('logo is published ✔');

        $this->info('Greeting!.. Enjoy Laravel PWA 🎉');

    }
}
