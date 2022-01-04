<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotspots;
use App\Models\User;
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
            'name' => $name,
        ]);

        //-- Get the current authenticated user
        $user = $request->user();

        //-- Save the hotspot with the user relationship
        $payload = $user->hotspots()->save($hotspot);

        // Build and return the response
        $response = [
            'success' => true,
            'payload' => $payload
        ];

        return response($response, 201);

    }

    public function getHotSpots(Request $request) {

        $hotspots = User::find($request->user()->id)->hotspots;
        $count = count($hotspots);

        $response = [
            'hotspots' => $count,
            'results' => $hotspots,
            'owner' => $request->user()
        ];
        return response($response,201);
    }

    public function deleteHotspot(Request $request) {
    $hotspot = Hotspots::find($request->id);

    $deleted = $hotspot->delete();

        $response = [
            'deleted' => $deleted
        ];
        return response($response, 201);
    }

}