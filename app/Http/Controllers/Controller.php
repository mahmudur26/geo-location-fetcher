<?php

namespace App\Http\Controllers;

use App\Models\GeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use function Psy\debug;
use Torann\GeoIP\GeoIP;

class Controller
{
    public function index(Request $request){

        return view('landing_page');
    }

    public function geo_info_store(Request $request){
        $ip = '103.167.17.138';

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
            'íp' => $ip,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'continent' => $data['results'][0]['components']['continent'],
            'country' => $data['results'][0]['components']['country'],
            'state' => $data['results'][0]['components']['state'],
            'state_district' => $data['results'][0]['components']['state_district'],
            'town' => $data['results'][0]['components']['town'],
            'full_formatted_address' => $data['results'][0]['formatted'],
            'accuracy_in_meter' => $data['results'][0]['distance_from_q']['meters']
        ];

        GeoData::query()->insert($formatted_data);
    }
}
