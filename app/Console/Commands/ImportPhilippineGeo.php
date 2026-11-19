<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportPhilippineGeo extends Command
{
    protected $signature = 'geo:import {--base-url=https://psgc.gitlab.io/PSGC-CSV}';
    protected $description = 'Download Philippine regions/provinces/cities/barangays CSVs and seed database';

    public function handle(): int
    {
        $baseUrl = rtrim($this->option('base-url'), '/');
        $files = [
            'refregion.csv',
            'refprovince.csv',
            'refcitymun.csv',
            'refbrgy.csv',
        ];

        $targetDir = database_path('seeders/data');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        foreach ($files as $file) {
            $url = $baseUrl . '/' . $file;
            $this->info('Downloading: ' . $url);
            try {
                $content = @file_get_contents($url);
                if ($content === false) {
                    $this->error('Failed to download ' . $url);
                    return self::FAILURE;
                }
                file_put_contents($targetDir . DIRECTORY_SEPARATOR . $file, $content);
                $this->info('Saved: ' . $file);
            } catch (\Throwable $e) {
                $this->error('Error downloading ' . $file . ': ' . $e->getMessage());
                return self::FAILURE;
            }
        }

        $this->info('Seeding geographic data...');
        Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\PhilippineGeoSeeder', '--no-interaction' => true]);
        $this->line(Artisan::output());

        $this->info('Done.');
        return self::SUCCESS;
    }
}


