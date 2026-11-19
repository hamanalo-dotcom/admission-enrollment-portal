<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProvinces extends Command
{
    protected $signature = 'import:provinces {file}';
    protected $description = 'Import provinces from a CSV file';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error('File not found: ' . $file);
            return;
        }
        $rows = array_map('str_getcsv', file($file));
        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // skip header
            DB::table('refprovince')->updateOrInsert(
                ['provCode' => $row[4]],
                [
                    'provCode' => $row[4],
                    'provDesc' => $row[2],
                    'psgcCode' => $row[1],
                    'regCode' => $row[3]
                ]
            );
        }
        $this->info('Provinces imported!');
    }
}
