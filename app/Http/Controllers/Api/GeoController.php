<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use App\Models\Barangay;

class GeoController extends Controller
{
    public function regions()
    {
        return response()->json($this->fetch('regions', [], function () {
            // Use cached PSGC regions
            $regions = Cache::remember('psgc_regions', 86400, function () {
                $resp = Http::timeout(15)->get('https://psgc.vercel.app/api/region');
                if (!$resp->ok()) return [];
                return $resp->json() ?? [];
            });
            return collect($regions)->map(function ($r) {
                return [
                    'code' => $r['code'] ?? null,
                    'name' => $r['name'] ?? ''
                ];
            })->filter(fn($r) => $r['code'])->values();
        }));
    }

    public function provinces(Request $request)
    {
        $regCode = $request->query('region_code');
        return response()->json($this->fetch('provinces', ['region_code' => $regCode], function () use ($regCode) {
            $provinces = Cache::remember('psgc_provinces', 86400, function () {
                $resp = Http::timeout(15)->get('https://psgc.vercel.app/api/province');
                if (!$resp->ok()) return [];
                return $resp->json() ?? [];
            });
            return collect($provinces)
                ->filter(fn($p) => (string)($p['regionCode'] ?? '') === (string)$regCode)
                ->map(fn($p) => [ 'code' => $p['code'] ?? null, 'name' => $p['name'] ?? '' ])
                ->filter(fn($p) => $p['code'])
                ->values();
        }));
    }

    public function cities(Request $request)
    {
        $provCode = $request->query('province_code');
        return response()->json($this->fetch('cities', ['province_code' => $provCode], function () use ($provCode) {
            $cities = Cache::remember('psgc_cities', 86400, function () {
                $resp = Http::timeout(15)->get('https://psgc.vercel.app/api/city-municipality');
                if (!$resp->ok()) return [];
                return $resp->json() ?? [];
            });
            return collect($cities)
                ->filter(fn($c) => (string)($c['provinceCode'] ?? '') === (string)$provCode)
                ->map(fn($c) => [ 'code' => $c['code'] ?? null, 'name' => $c['name'] ?? '' ])
                ->filter(fn($c) => $c['code'])
                ->values();
        }));
    }

    public function barangays(Request $request)
    {
        $cityCode = $request->query('city_code');
        return response()->json($this->fetch('barangays', ['city_code' => $cityCode], function () use ($cityCode) {
            $brgys = Cache::remember('psgc_barangays', 86400, function () {
                $resp = Http::timeout(30)->get('https://psgc.vercel.app/api/barangay');
                if (!$resp->ok()) return [];
                return $resp->json() ?? [];
            });
            return collect($brgys)
                ->filter(fn($b) => (string)($b['cityCode'] ?? '') === (string)$cityCode)
                ->map(fn($b) => [ 'code' => $b['code'] ?? null, 'name' => $b['name'] ?? '' ])
                ->filter(fn($b) => $b['code'])
                ->values();
        }));
    }

    private function fetch(string $type, array $params, \Closure $resolver)
    {
        try {
            return [
                'type' => $type,
                'params' => $params,
                'data' => $resolver(),
            ];
        } catch (\Throwable $e) {
            return [
                'type' => $type,
                'params' => $params,
                'data' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    // Static complete list of regions OFFLINE
    public function regionsAll()
    {
        if (!\Schema::hasTable('refregion')) {
            return response()->json(['type' => 'regions_all', 'data' => []]);
        }
        $data = \App\Models\Region::all()->map(fn($r) => [
            'code' => $r->regCode,
            'name' => $r->regDesc
        ])->values();
        return response()->json(['type' => 'regions_all', 'data' => $data]);
    }
    public function provincesAll()
    {
        if (!\Schema::hasTable('refprovince')) {
            return response()->json(['type' => 'provinces_all', 'data' => []]);
        }
        $data = \App\Models\Province::all()->map(fn($p) => [
            'code' => $p->provCode,
            'name' => $p->provDesc,
            'region_code' => $p->regCode
        ])->values();
        return response()->json(['type' => 'provinces_all', 'data' => $data]);
    }
    public function citiesAll()
    {
        if (!\Schema::hasTable('refcitymun')) {
            return response()->json(['type' => 'cities_all', 'data' => []]);
        }
        $data = \App\Models\City::all()->map(fn($c) => [
            'code' => $c->citymunCode,
            'name' => $c->citymunDesc,
            'province_code' => $c->provCode
        ])->values();
        return response()->json(['type' => 'cities_all', 'data' => $data]);
    }
    public function barangaysAll()
    {
        if (!\Schema::hasTable('refbrgy')) {
            return response()->json(['type' => 'barangays_all', 'data' => []]);
        }
        $data = \App\Models\Barangay::all()->map(fn($b) => [
            'code' => $b->brgyCode,
            'name' => $b->brgyDesc,
            'city_code' => $b->citymunCode
        ])->values();
        return response()->json(['type' => 'barangays_all', 'data' => $data]);
    }
}


