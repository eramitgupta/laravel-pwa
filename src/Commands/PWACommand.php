<?php

namespace EragLaravelPwa\Commands;

use EragLaravelPwa\Services\PWAService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class PWACommand extends Command
{
    protected $signature = 'erag:update-manifest';

    protected $description = 'Update the manifest.json file for the PWA.';

    public function __construct(protected PWAService $pwaService)
    {
        parent::__construct();
    }

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

            $manifest = Config::get('pwa.manifest', $defaultManifest);

            if (empty($manifest['icons'])) {
                $this->error('⚠️  Manifest is missing icons. Operation aborted.');

                return;
            }

            $updated = $this->pwaService->createOrUpdate($manifest);

            if ($updated) {
                $this->info('✅ Manifest JSON updated successfully.');
            } else {
                $this->warn('⚠️  Manifest file was not updated.');
            }
        } catch (\Throwable $e) {
            $this->error('❌ An error occurred while updating the manifest: '.$e->getMessage());
        }
    }
}
