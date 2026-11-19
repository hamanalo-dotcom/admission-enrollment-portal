<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportRegions extends Command
{
    protected $signature = 'import:regions {file}';
    protected $description = 'Import regions from a CSV file';

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
            DB::table('refregion')->updateOrInsert(
                ['regCode' => $row[3]],
                [
                    'regCode' => $row[3],
                    'regDesc' => $row[2],
                    'psgcCode' => $row[1]
                ]
            );
        }
        $this->info('Regions imported!');
    }
}
