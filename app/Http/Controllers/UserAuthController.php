<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class UserAuthController extends Controller
{
    // public function login(Request $request) {
        
    //     $user = User::where('email', '=', $request->user)->first();

    //     if (Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'status' => 200,
    //             'user' => $user
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 401,
    //             'message' => 'Invalid Login'
    //         ]);
    //     }
    // }

    // public function registerNewUser(Request $request) {

    //     if ($request->name && $request->email && $request->password) {
    //         $user = new User;

    //         $user->name = $request->name;
    //         $user->email = $request->email;
    //         $user->password = Hash::make($request->password);

    //         return $user->save();
    //     }

    //     return response()->json([
    //         'error' => 'Fields required'
    //     ]);

    // }

    public function register(Request $request) {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function login(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //check email
        $user = User::where('email', $fields['email'])->first();
        
        //Check Passsword
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid Credentials'
            ], 401);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return response($response, 201);
    }
}
