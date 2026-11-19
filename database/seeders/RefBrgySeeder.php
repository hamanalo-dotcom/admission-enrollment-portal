<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RefBrgySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            // Adams, Ilocos Norte (012801) examples:
            ['brgyCode' => '012801001', 'brgyDesc' => 'Adams Proper', 'citymunCode' => '012801'],
            ['brgyCode' => '012801002', 'brgyDesc' => 'Pansian',      'citymunCode' => '012801'],
            // Add more barangays per city/municipality as needed...
        ];
        foreach ($barangays as $brgy) {
            \DB::table('refbrgy')->updateOrInsert(
                ['brgyCode' => $brgy['brgyCode']], $brgy
            );
        }
    }
}
