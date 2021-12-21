<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Validator;

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



        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);
         
        if ($validator->fails()) {
             return response([
                    'err' => true,
                    'message' => $validator->messages()->first()
             ]);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
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
            'success' => true,
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return response($response, 201);
    }

    public function authUser(Request $request) {
        return response([
            'success' => true
        ], 201);
    }
}
