<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token =  $user->createToken($user->name . "'s_token")->plainTextToken;
        $user->remember_token = $token;
        $user->save();
        return [
            "token" => $token
        ];
    }
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5|max:25',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^01[0125][0-9]{8}$/i||unique:users',
            'password' => 'required|min:5|max:255',
        ]);
        try {
            User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'country' => $request->country,
                'city' => $request->city,
            ]);
        } catch (QueryException $e) {
            return response([
                "message" => "Internal server error !"
            ], 500);
        }

        return response([
            'message' => "User Created Successfuly."
        ], 201);
    }
    public function logout(Request $request)
    {
        $token = $request->header('token');
        if (!$token || !PersonalAccessToken::findToken($token)) {
            return response([
                'message' => 'Token error !'
            ], 400);
        }
        $token = PersonalAccessToken::findToken($token);
        $user = User::where('id', $token->tokenable_id)->first();
        $user->remember_token = null;
        $user->tokens()->where('id', $token->id)->delete();
        $user->save();
        return response([
            'message' => 'Logged out successfuly.'
        ]);
    }
}
