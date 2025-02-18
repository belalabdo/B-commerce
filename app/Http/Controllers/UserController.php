<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private function validate(Request $request, $required = "required")
    {
        $data = Validator::make($request->all(), [
            "name" => "$required|min:5|max:25",
            "email" => "$required|email|unique:users",
            "phone" => "$required|regex:/^01[0125][0-9]{8}$/i||unique:users",
            "password" => "$required|min:5|max:255",
            "profile_img" => "image|extensions:jpg,png,jpeg"
        ])->validate();
        return $data;
    }
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
        // Bad vaildation approach !
        $request->validate([
            'name' => 'required|min:5|max:25',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^01[0125][0-9]{8}$/i||unique:users',
            'password' => 'required|min:5|max:255',
            'profile_img' => 'image|extensions:jpg,png,jpeg'
        ]);
        $image_url = resolve(CloudinaryEngine::class)
            ->uploadFile($request->file('profile_img')->getRealPath())
            ->getSecurePath();

        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'country' => $request->country,
                'city' => $request->city,
                'profile_img' => $image_url,
            ]);
        } catch (QueryException $e) {
            return response([
                "message" => "Internal server error !"
            ], 500);
        }
        CartsController::create($user->id);
        WishlistsController::create($user->id);

        return response([
            'message' => "User Created Successfuly.",
            'user' => $user
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
    public function update(Request $request)
    {
        $newUserData = $this->validate($request, false);
        $token = PersonalAccessToken::findToken($request->header("token"));
        $user = User::find($token->tokenable_id);

        if (!$user) {
            return response([
                'message' => 'User not found'
            ], 404);
        }

        $user->update($newUserData);
        return response([
            'message' => 'User updated successfuly'
        ], 200);
    }
}
