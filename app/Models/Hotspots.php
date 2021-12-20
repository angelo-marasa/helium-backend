<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Hotspots;

class Hotspots extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'name'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function addHotspot(Request $request)
    {
        $hotspot = new Hotspots;

        $hotspot->name = $request->name;
        $hotspot->address = $request->address;
        $hotspot->user_id = $request->user;

        $hotspot->save();

        return $hotspot;
    }
}