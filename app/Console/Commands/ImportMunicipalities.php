<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportMunicipalities extends Command
{
    protected $signature = 'import:municipalities {file}';
    protected $description = 'Import municipalities/cities from a PSGC CSV file';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error('File not found: ' . $file);
            return;
        }
        $rows = array_map('str_getcsv', file($file));
        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // Skip header
            // Adjust column indices as per your CSV structure!
            DB::table('refcitymun')->updateOrInsert(
                ['citymunCode' => $row[5]],
                [
                    'citymunCode' => $row[5],     // city/municipality code (012801, ...)
                    'psgcCode' => $row[1],        // PSGC code (012801000, ...)
                    'citymunDesc' => $row[2],     // Name/description (e.g., ADAMS)
                    'provCode' => $row[4]         // Province code (0128)
                ]
            );
        }
        $this->info('Municipalities imported!');
    }
}