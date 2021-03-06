<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Hotspots;

use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function home() {
        return view('welcome');
    }
    public function hotspots() {
        $hotspots = User::find(12)->hotspots;

        dd($hotspots);
    }

    public function users() {
        $user = hotspots::find(1)->user;

        dd($user);
    }
}
