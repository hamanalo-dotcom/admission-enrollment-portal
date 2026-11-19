<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhilippineGeoSeeder extends Seeder
{
    public function run(): void
    {
        $base = base_path('csv');
        
        // Disable foreign key checks and clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('refbrgy')->truncate();
        DB::table('refcitymun')->truncate();
        DB::table('refprovince')->truncate();
        DB::table('refregion')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // CSV format: id, psgcCode, regDesc, regCode
        $this->importCsv($base . '/refregion.csv', 'refregion', [
            1 => 'psgcCode',
            2 => 'regDesc', 
            3 => 'regCode'
        ], 'regCode');
        
        // CSV format: id, psgcCode, provDesc, regCode, provCode
        $this->importCsv($base . '/refprovince.csv', 'refprovince', [
            1 => 'psgcCode',
            2 => 'provDesc',
            3 => 'regCode',
            4 => 'provCode'
        ], 'provCode');
        
        // CSV format: id, psgcCode, citymunDesc, regDesc, provCode, citymunCode
        $this->importCsv($base . '/refcitymun.csv', 'refcitymun', [
            1 => 'psgcCode',
            2 => 'citymunDesc',
            4 => 'provCode',
            5 => 'citymunCode'
        ], 'citymunCode');
        
        // CSV format: id, brgyCode, brgyDesc, regCode, provCode, citymunCode
        $this->importCsv($base . '/refbrgy.csv', 'refbrgy', [
            1 => 'brgyCode',
            2 => 'brgyDesc',
            5 => 'citymunCode'
        ], 'brgyCode');
    }

    private function importCsv(string $path, string $table, array $columnMap, string $uniqueKey): void
    {
        if (!file_exists($path)) {
            $this->command?->warn("CSV not found: {$path}. Skipping {$table}.");
            return;
        }
        $handle = fopen($path, 'r');
        if (!$handle) return;

        // Skip header
        fgetcsv($handle);

        $batch = [];
        $seen = [];
        $count = 0;
        $skipped = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            $row = array_map('trim', $row);
            $data = [];
            foreach ($columnMap as $index => $col) {
                $data[$col] = $row[$index] ?? null;
            }
            
            // Skip duplicates based on unique key
            $keyValue = $data[$uniqueKey] ?? null;
            if ($keyValue && isset($seen[$keyValue])) {
                $skipped++;
                continue;
            }
            $seen[$keyValue] = true;
            
            $batch[] = $data;
            if (count($batch) >= 1000) {
                DB::table($table)->insert($batch);
                $count += count($batch);
                $batch = [];
            }
        }
        if ($batch) {
            DB::table($table)->insert($batch);
            $count += count($batch);
        }

        fclose($handle);
        $msg = "Imported {$count} rows into {$table}.";
        if ($skipped > 0) {
            $msg .= " (Skipped {$skipped} duplicates)";
        }
        $this->command?->info($msg);
    }
}


