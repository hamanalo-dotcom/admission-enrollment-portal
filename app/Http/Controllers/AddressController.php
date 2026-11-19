<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;

class AddressController extends Controller
{
    public function getProvinces($region_id)
    {
        $provinces = Province::where('region_id', $region_id)->get();
        return response()->json($provinces);
    }

    public function getCities($province_id)
    {
        $cities = City::where('province_id', $province_id)->get();
        return response()->json($cities);
    }

    public function getBarangays($city_id)
    {
        $barangays = Barangay::where('city_id', $city_id)->get();
        return response()->json($barangays);
    }
}
