<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportBarangays extends Command
{
    protected $signature = 'import:barangays {file}';
    protected $description = 'Import barangays from a PSGC CSV file';

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
            DB::table('refbrgy')->updateOrInsert(
                ['brgyCode' => $row[1]],
                [
                    'brgyCode' => $row[1],
                    'brgyDesc' => $row[2],
                    'citymunCode' => $row[5]
                ]
            );
        }
        $this->info('Barangays imported!');
    }
}