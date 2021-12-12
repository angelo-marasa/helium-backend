<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function login(Request $request) {
        
        $user = User::where('email', '=', $request->user)->first();

        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 200,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Login'
            ]);
        }
    }

    public function registerNewUser(Request $request) {

        if ($request->name && $request->email && $request->password) {
            $user = new User;

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            return $user->save();
        }

        return response()->json([
            'error' => 'Fields required'
        ]);

    }
}
