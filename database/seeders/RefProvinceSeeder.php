<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Complete PSGC province data. Each region grouped for clarity.
        $provinces = [
            // REGION I: Ilocos Region
            ['provCode' => '0128', 'provDesc' => 'Ilocos Norte',      'regCode' => '01'],
            ['provCode' => '0129', 'provDesc' => 'Ilocos Sur',        'regCode' => '01'],
            ['provCode' => '0133', 'provDesc' => 'La Union',          'regCode' => '01'],
            ['provCode' => '0155', 'provDesc' => 'Pangasinan',        'regCode' => '01'],
            // REGION II: Cagayan Valley
            ['provCode' => '0209', 'provDesc' => 'Batanes',           'regCode' => '02'],
            ['provCode' => '0215', 'provDesc' => 'Cagayan',           'regCode' => '02'],
            ['provCode' => '0231', 'provDesc' => 'Isabela',           'regCode' => '02'],
            ['provCode' => '0250', 'provDesc' => 'Nueva Vizcaya',     'regCode' => '02'],
            ['provCode' => '0257', 'provDesc' => 'Quirino',           'regCode' => '02'],
            // REGION III: Central Luzon
            ['provCode' => '0308', 'provDesc' => 'Bataan',            'regCode' => '03'],
            ['provCode' => '0314', 'provDesc' => 'Bulacan',           'regCode' => '03'],
            ['provCode' => '0349', 'provDesc' => 'Nueva Ecija',       'regCode' => '03'],
            ['provCode' => '0354', 'provDesc' => 'Pampanga',          'regCode' => '03'],
            ['provCode' => '0369', 'provDesc' => 'Tarlac',            'regCode' => '03'],
            ['provCode' => '0371', 'provDesc' => 'Zambales',          'regCode' => '03'],
            ['provCode' => '0377', 'provDesc' => 'Aurora',            'regCode' => '03'],
            // REGION IV-A: CALABARZON
            ['provCode' => '0410', 'provDesc' => 'Batangas',           'regCode' => '04'],
            ['provCode' => '0421', 'provDesc' => 'Cavite',             'regCode' => '04'],
            ['provCode' => '0434', 'provDesc' => 'Laguna',             'regCode' => '04'],
            ['provCode' => '0456', 'provDesc' => 'Quezon',             'regCode' => '04'],
            ['provCode' => '0458', 'provDesc' => 'Rizal',              'regCode' => '04'],
            // REGION IV-B: MIMAROPA
            ['provCode' => '1751', 'provDesc' => 'Marinduque',         'regCode' => '17'],
            ['provCode' => '1752', 'provDesc' => 'Occidental Mindoro', 'regCode' => '17'],
            ['provCode' => '1753', 'provDesc' => 'Oriental Mindoro',   'regCode' => '17'],
            ['provCode' => '1759', 'provDesc' => 'Palawan',            'regCode' => '17'],
            ['provCode' => '1740', 'provDesc' => 'Romblon',            'regCode' => '17'],
            // REGION V: Bicol
            ['provCode' => '0505', 'provDesc' => 'Albay',              'regCode' => '05'],
            ['provCode' => '0516', 'provDesc' => 'Camarines Norte',    'regCode' => '05'],
            ['provCode' => '0517', 'provDesc' => 'Camarines Sur',      'regCode' => '05'],
            ['provCode' => '0520', 'provDesc' => 'Catanduanes',        'regCode' => '05'],
            ['provCode' => '0541', 'provDesc' => 'Masbate',            'regCode' => '05'],
            ['provCode' => '0562', 'provDesc' => 'Sorsogon',           'regCode' => '05'],
            // REGION VI: Western Visayas
            ['provCode' => '0604', 'provDesc' => 'Aklan',              'regCode' => '06'],
            ['provCode' => '0606', 'provDesc' => 'Antique',            'regCode' => '06'],
            ['provCode' => '0619', 'provDesc' => 'Capiz',              'regCode' => '06'],
            ['provCode' => '0630', 'provDesc' => 'Iloilo',             'regCode' => '06'],
            ['provCode' => '0645', 'provDesc' => 'Negros Occidental',  'regCode' => '06'],
            ['provCode' => '0682', 'provDesc' => 'Guimaras',           'regCode' => '06'],
            // REGION VII: Central Visayas
            ['provCode' => '0712', 'provDesc' => 'Bohol',              'regCode' => '07'],
            ['provCode' => '0722', 'provDesc' => 'Cebu',               'regCode' => '07'],
            ['provCode' => '0746', 'provDesc' => 'Negros Oriental',    'regCode' => '07'],
            ['provCode' => '0761', 'provDesc' => 'Siquijor',           'regCode' => '07'],
            // REGION VIII: Eastern Visayas
            ['provCode' => '0826', 'provDesc' => 'Biliran',            'regCode' => '08'],
            ['provCode' => '0837', 'provDesc' => 'Eastern Samar',      'regCode' => '08'],
            ['provCode' => '0839', 'provDesc' => 'Leyte',              'regCode' => '08'],
            ['provCode' => '0848', 'provDesc' => 'Northern Samar',     'regCode' => '08'],
            ['provCode' => '0860', 'provDesc' => 'Samar (Western Samar)','regCode' => '08'],
            ['provCode' => '0864', 'provDesc' => 'Southern Leyte',     'regCode' => '08'],
            // REGION IX: Zamboanga Peninsula
            ['provCode' => '0972', 'provDesc' => 'Zamboanga del Norte','regCode' => '09'],
            ['provCode' => '0973', 'provDesc' => 'Zamboanga del Sur',  'regCode' => '09'],
            ['provCode' => '0983', 'provDesc' => 'Zamboanga Sibugay',  'regCode' => '09'],
            // REGION X: Northern Mindanao
            ['provCode' => '1013', 'provDesc' => 'Bukidnon',           'regCode' => '10'],
            ['provCode' => '1018', 'provDesc' => 'Camiguin',           'regCode' => '10'],
            ['provCode' => '1035', 'provDesc' => 'Lanao del Norte',    'regCode' => '10'],
            ['provCode' => '1042', 'provDesc' => 'Misamis Occidental', 'regCode' => '10'],
            ['provCode' => '1043', 'provDesc' => 'Misamis Oriental',   'regCode' => '10'],
            // REGION XI: Davao
            ['provCode' => '1123', 'provDesc' => 'Davao de Oro',        'regCode' => '11'],
            ['provCode' => '1124', 'provDesc' => 'Davao del Norte',     'regCode' => '11'],
            ['provCode' => '1125', 'provDesc' => 'Davao del Sur',       'regCode' => '11'],
            ['provCode' => '1182', 'provDesc' => 'Davao Occidental',    'regCode' => '11'],
            ['provCode' => '1126', 'provDesc' => 'Davao Oriental',      'regCode' => '11'],
            // REGION XII: SOCCSKSARGEN
            ['provCode' => '1247', 'provDesc' => 'Cotabato (North Cotabato)','regCode' => '12'],
            ['provCode' => '1263', 'provDesc' => 'Sarangani',            'regCode' => '12'],
            ['provCode' => '1265', 'provDesc' => 'South Cotabato',      'regCode' => '12'],
            ['provCode' => '1280', 'provDesc' => 'Sultan Kudarat',      'regCode' => '12'],
            // CARAGA: Region XIII
            ['provCode' => '1380', 'provDesc' => 'Agusan del Norte',    'regCode' => '16'],
            ['provCode' => '1401', 'provDesc' => 'Agusan del Sur',      'regCode' => '16'],
            ['provCode' => '1411', 'provDesc' => 'Dinagat Islands',     'regCode' => '16'],
            ['provCode' => '1427', 'provDesc' => 'Surigao del Norte',   'regCode' => '16'],
            ['provCode' => '1431', 'provDesc' => 'Surigao del Sur',     'regCode' => '16'],
            // NCR: National Capital Region
            ['provCode' => '1339', 'provDesc' => 'Metropolitan Manila', 'regCode' => '13'],
            // CAR: Cordillera
            ['provCode' => '1401', 'provDesc' => 'Abra',                'regCode' => '14'],
            ['provCode' => '1402', 'provDesc' => 'Apayao',              'regCode' => '14'],
            ['provCode' => '1411', 'provDesc' => 'Benguet',             'regCode' => '14'],
            ['provCode' => '1427', 'provDesc' => 'Ifugao',              'regCode' => '14'],
            ['provCode' => '1432', 'provDesc' => 'Kalinga',             'regCode' => '14'],
            ['provCode' => '1444', 'provDesc' => 'Mountain Province',   'regCode' => '14'],
            // BARMM
            ['provCode' => '1536', 'provDesc' => 'Basilan',             'regCode' => '19'],
            ['provCode' => '1538', 'provDesc' => 'Lanao del Sur',       'regCode' => '19'],
            ['provCode' => '1566', 'provDesc' => 'Maguindanao del Norte','regCode' => '19'],
            ['provCode' => '1570', 'provDesc' => 'Maguindanao del Sur',  'regCode' => '19'],
            ['provCode' => '1602', 'provDesc' => 'Sulu',                'regCode' => '19'],
            ['provCode' => '1603', 'provDesc' => 'Tawi-Tawi',           'regCode' => '19'],
        ];
        foreach ($provinces as $province) {
            \DB::table('refprovince')->updateOrInsert([
                'provCode' => $province['provCode']
            ], $province);
        }
    }
}
