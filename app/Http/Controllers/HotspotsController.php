<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotspots;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotspotsController extends Controller
{
    public function addHotspot(Request $request) {
        

        //-- API request to get hotspot details by address.
        $response = Http::withHeaders([
            'user-agent' => 'Helium Script'
        ])->get(ENV('HELIUM_API_ENDPOINT').'/hotspots/'. $request->address);

        //-- We want JSON type data
        $hotspot_data = $response->json();

        //-- Convert slug name to nice name
        $name = ucwords(str_replace("-", " ", $hotspot_data['data']['name']));


        $hotspot = new Hotspots([
            'address' => $request->address,
            'name' => $name
        ]);

        //-- Get the current authenticated user
        $user = $request->user();

        //-- Save the hotspot with the user relationship
        $payload = $user->hotspots()->save($hotspot);

        $response = [
            'success' => true,
            'payload' => $payload
        ];

        return response($response, 201);

    }
}
