<?php

namespace EragLaravelPwa\Commands;

use EragLaravelPwa\Services\PWAService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class PWACommand extends Command
{
    public function __construct(protected PWAService $pwaService)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erag:update-manifest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the manifest.json file for the PWA.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $defaultManifest = [
                'name' => 'Laravel PWA',
                'short_name' => 'LPT',
                'background_color' => '#000000',
                'display' => 'fullscreen',
                'description' => 'A Progressive Web Application setup for Laravel projects.',
                'theme_color' => '#000000',
                'icons' => [
                    [
                        'src' => 'logo.png',
                        'sizes' => '512x512',
                        'type' => 'image/png',
                    ],
                ],
            ];

            // Load custom manifest from config, fallback to default
            $manifest = Config::get('pwa.manifest', $defaultManifest);

            if (empty($manifest['icons'])) {
                $this->error('Manifest is missing icons. Aborting operation.');

                return;
            }

            if ($this->pwaService->createOrUpdate($manifest)) {
                $this->info('Manifest JSON updated successfully ✔');
            }

            $this->info('Failed to write the manifest file.');

        } catch (\Exception $e) {
            // Catch any errors and display an error message
            $this->error('An error occurred while updating the manifest: '.$e->getMessage());
        }
    }
}
