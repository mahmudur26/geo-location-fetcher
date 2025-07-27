<?php

namespace App\Http\Controllers;

use App\Models\GeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller
{
    public function index(){
        $geo_data = GeoData::query()->orderByDesc('id')->get();
        foreach($geo_data as $item){
            $data['geo_data'][] = [
                'ip' => $item->ip,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'full_address' => $item->full_formatted_address,
                'accuracy_in_meter' => $item->accuracy_in_meter,
            ];
            $data['coordinates'][] = [$item->latitude, $item->longitude];
        }
        $data['geo_data'] = array_slice($data['geo_data'], 0, 5);
//dd($data);
        return view('landing_page')->with($data)    ;
    }

    public function geo_info_store(Request $request){
        return response()->json(['status' => 'success']);

        $ip = request()->ip();

        $latitude = $request->input('latitude') ?? NULL;
        $longitude = $request->input('longitude') ?? NULL;

        if(!$latitude || !$longitude){
            $response = Http::get("http://ip-api.com/json/{$ip}");
            $fetched_data = $response->json();

            $latitude = $fetched_data['lat'];
            $longitude = $fetched_data['lon'];
        }

        $response = Http::get("https://api.opencagedata.com/geocode/v1/json", [
            'key' => 'd5545257f1304cda8761998c2da5b6d2',
            'q' => "{$latitude},{$longitude}",
            'language' => 'en',
            'pretty' => 1,
        ]);

        $data = $response->json();
        $formatted_data = [
            'ip' => $ip,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'continent' => $data['results'][0]['components']['continent'] ?? NULL,
            'country' => $data['results'][0]['components']['country'] ?? NULL,
            'state' => $data['results'][0]['components']['state'] ?? NULL,
            'state_district' => $data['results'][0]['components']['state_district'] ?? NULL,
            'town' => $data['results'][0]['components']['town'] ?? NULL,
            'full_formatted_address' => $data['results'][0]['formatted'] ?? NULL,
            'accuracy_in_meter' => $data['results'][0]['distance_from_q']['meters'] ?? NULL
        ];

        GeoData::query()->create($formatted_data);

        Log::info("All good here");

        return response()->json(['status' => 'success']);
    }
}
