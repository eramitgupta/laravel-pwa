<?php

namespace EragLaravelPwa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PWACommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erag:pwa-update-manifest';

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

            unset($manifest['start_url']);
            $icons = $manifest['icons'];
            unset($manifest['icons']);

            $arrayMergeManifest = array_merge($manifest, ['start_url' => '/'], ['icons' => $icons]);

            $jsonData = json_encode($arrayMergeManifest, JSON_PRETTY_PRINT);
            if ($jsonData === false) {
                $this->error('Failed to encode manifest array to JSON. Aborting operation.');

                return;
            }

            $jsonData = str_replace('\/', '/', $jsonData);

            $filePath = public_path('manifest.json');
            if (! File::isWritable(public_path())) {
                $this->error('Public directory is not writable. Check file permissions.');

                return;
            }

            File::put($filePath, $jsonData);

            $this->info('Manifest JSON updated successfully ✔');
        } catch (\Exception $e) {
            // Catch any errors and display an error message
            $this->error('An error occurred while updating the manifest: '.$e->getMessage());
        }
    }
}
