<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegionApiController extends Controller
{
    protected function getApiKey()
    {
        return env('API_INDONESIA_KEY');
    }

    public function getProvinces(Request $request)
    {
        $apiKey = $this->getApiKey();

        if (empty($apiKey)) {
            try {
                $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
                if ($response->successful()) {
                    $provinces = collect($response->json())->map(function($item) {
                        return [
                            'id' => $item['id'],
                            'name' => strtoupper($item['name'])
                        ];
                    })->toArray();
                    return response()->json(['data' => $provinces]);
                }
            } catch (\Exception $e) {
                Log::error('Emsifa provinces fallback error: ' . $e->getMessage());
            }

            // Ultimate fallback mock data if even emsifa fails
            return response()->json([
                'data' => [
                    ['id' => '31', 'name' => 'DKI JAKARTA'],
                    ['id' => '32', 'name' => 'JAWA BARAT'],
                    ['id' => '33', 'name' => 'JAWA TENGAH'],
                    ['id' => '34', 'name' => 'DI YOGYAKARTA'],
                    ['id' => '35', 'name' => 'JAWA TIMUR'],
                    ['id' => '36', 'name' => 'BANTEN'],
                ]
            ]);
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey
            ])->get('https://use.apiindonesia.id/api/v1/wilayah/provinsi', [
                'per_page' => 100
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('API Indonesia error fetching provinces: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('API Indonesia exception fetching provinces: ' . $e->getMessage());
        }

        return response()->json(['data' => []], 500);
    }

    public function getCities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $apiKey = $this->getApiKey();

        if (empty($apiKey)) {
            try {
                $response = Http::get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinceId}.json");
                if ($response->successful()) {
                    $cities = collect($response->json())->map(function($item) {
                        return [
                            'id' => $item['id'],
                            'name' => strtoupper($item['name'])
                        ];
                    })->toArray();
                    return response()->json(['data' => $cities]);
                }
            } catch (\Exception $e) {
                Log::error("Emsifa regencies fallback error for province {$provinceId}: " . $e->getMessage());
            }

            $mockCities = [
                '31' => [ // Jakarta
                    ['id' => '3171', 'name' => 'KOTA JAKARTA PUSAT'],
                    ['id' => '3173', 'name' => 'KOTA JAKARTA BARAT'],
                    ['id' => '3174', 'name' => 'KOTA JAKARTA SELATAN'],
                    ['id' => '3175', 'name' => 'KOTA JAKARTA TIMUR'],
                ],
                '32' => [ // Jawa Barat
                    ['id' => '3273', 'name' => 'KOTA BANDUNG'],
                    ['id' => '3276', 'name' => 'KOTA DEPOK'],
                    ['id' => '3271', 'name' => 'KOTA BOGOR'],
                ],
                '34' => [ // DIY
                    ['id' => '3471', 'name' => 'KOTA YOGYAKARTA'],
                    ['id' => '3404', 'name' => 'KABUPATEN SLEMAN'],
                ],
                '35' => [ // Jawa Timur
                    ['id' => '3578', 'name' => 'KOTA SURABAYA'],
                    ['id' => '3573', 'name' => 'KOTA MALANG'],
                ],
            ];

            $data = $mockCities[$provinceId] ?? [];
            return response()->json(['data' => $data]);
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey
            ])->get('https://use.apiindonesia.id/api/v1/wilayah/kabupaten', [
                'provinsi_id' => $provinceId,
                'per_page' => 100
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error('API Indonesia error fetching cities: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('API Indonesia exception fetching cities: ' . $e->getMessage());
        }

        return response()->json(['data' => []], 500);
    }

    public function getCampuses(Request $request)
    {
        $provinceId = $request->query('province_id');
        $cityId = $request->query('city_id');
        $cityName = $request->query('city_name');
        $apiKey = $this->getApiKey();

        $mockCampuses = [
            // Depok (3276)
            '3276' => [
                ['id' => 'pt_002', 'name' => 'UNIVERSITAS INDONESIA (UI)'],
                ['id' => 'pt_003', 'name' => 'UNIVERSITAS GUNADARMA'],
            ],
            // Bandung (3273)
            '3273' => [
                ['id' => 'pt_001', 'name' => 'INSTITUT TEKNOLOGI BANDUNG (ITB)'],
                ['id' => 'pt_004', 'name' => 'UNIVERSITAS PADJADJARAN (UNPAD)'],
                ['id' => 'pt_005', 'name' => 'UNIVERSITAS PENDIDIKAN INDONESIA (UPI)'],
                ['id' => 'pt_006', 'name' => 'TELKOM UNIVERSITY'],
            ],
            // Jakarta Selatan (3174)
            '3174' => [
                ['id' => 'pt_007', 'name' => 'UNIVERSITAS NEGERI JAKARTA (UNJ)'],
                ['id' => 'pt_008', 'name' => 'UNIVERSITAS PANCASILA'],
            ],
            // Surabaya (3578)
            '3578' => [
                ['id' => 'pt_009', 'name' => 'UNIVERSITAS AIRLANGGA (UNAIR)'],
                ['id' => 'pt_010', 'name' => 'INSTITUT TEKNOLOGI SEPULUH NOPEMBER (ITS)'],
                ['id' => 'pt_011', 'name' => 'UNIVERSITAS NEGERI SURABAYA (UNESA)'],
            ],
            // Malang (3573)
            '3573' => [
                ['id' => 'pt_012', 'name' => 'UNIVERSITAS BRAWIJAYA (UB)'],
                ['id' => 'pt_013', 'name' => 'UNIVERSITAS NEGERI MALANG (UM)'],
                ['id' => 'pt_014', 'name' => 'UNIVERSITAS MUHAMMADIYAH MALANG (UMM)'],
            ],
            // Sleman (3404)
            '3404' => [
                ['id' => 'pt_015', 'name' => 'UNIVERSITAS GADJAH MADA (UGM)'],
                ['id' => 'pt_016', 'name' => 'UNIVERSITAS NEGERI YOGYAKARTA (UNY)'],
                ['id' => 'pt_017', 'name' => 'UNIVERSITAS ISLAM INDONESIA (UII)'],
            ],
        ];

        if (empty($apiKey)) {
            if ($cityId && isset($mockCampuses[$cityId])) {
                return response()->json(['data' => $mockCampuses[$cityId]]);
            }
            $allFlat = [];
            foreach ($mockCampuses as $cId => $list) {
                $allFlat = array_merge($allFlat, $list);
            }
            return response()->json(['data' => $allFlat]);
        }

        $results = [];

        // If city name is provided, clean and search by keyword
        if ($cityName) {
            $keyword = trim(str_ireplace(['kota ', 'kabupaten ', 'kab. ', 'kota/kabupaten '], '', $cityName));
            if (strlen($keyword) >= 2) {
                try {
                    $searchResponse = Http::withHeaders([
                        'x-api-key' => $apiKey
                    ])->get('https://use.apiindonesia.id/api/v1/kampus/search', [
                        'q' => $keyword
                    ]);

                    if ($searchResponse->successful()) {
                        $results = $searchResponse->json()['data'] ?? [];
                    }
                } catch (\Exception $e) {
                    Log::error("API Indonesia search campuses error for keyword {$keyword}: " . $e->getMessage());
                }
            }
        }

        // Fallback to filtering by ID if search yielded no results
        if (empty($results)) {
            try {
                $params = [];
                if ($provinceId) {
                    $params['provinsi_id'] = $provinceId;
                }
                if ($cityId) {
                    $params['kabupaten_id'] = $cityId;
                }
                $params['per_page'] = 100;

                $response = Http::withHeaders([
                    'x-api-key' => $apiKey
                ])->get('https://use.apiindonesia.id/api/v1/kampus', $params);

                if ($response->successful()) {
                    $results = $response->json()['data'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error('API Indonesia standard campuses error: ' . $e->getMessage());
            }
        }

        // If cityId matches local mocks, merge them so key universities (like UI in Depok) always show up
        if ($cityId && isset($mockCampuses[$cityId])) {
            $localMocks = $mockCampuses[$cityId];
            // Remove duplicates
            $existingNames = array_map(function($item) {
                return strtolower($item['name']);
            }, $results);

            foreach ($localMocks as $mock) {
                if (!in_array(strtolower($mock['name']), $existingNames)) {
                    $results[] = $mock;
                }
            }
        }

        return response()->json(['data' => $results]);
    }
}
