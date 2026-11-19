<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RefCityMunSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // Ilocos Norte (0128) examples:
            ['citymunCode' => '012801', 'citymunDesc' => 'Adams',   'provCode' => '0128'],
            ['citymunCode' => '012802', 'citymunDesc' => 'Bacarra', 'provCode' => '0128'],
            ['citymunCode' => '012803', 'citymunDesc' => 'Badoc',   'provCode' => '0128'],
            ['citymunCode' => '012804', 'citymunDesc' => 'Bangui',  'provCode' => '0128'],
            // Add more as needed by province...
        ];
        foreach ($cities as $city) {
            \DB::table('refcitymun')->updateOrInsert(
                ['citymunCode' => $city['citymunCode']], $city
            );
        }
    }
}
