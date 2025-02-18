<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class IsAdminRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $token = $request->header("token");
        // if (!$token || !PersonalAccessToken::findToken($token)) {
        //     return response([
        //         "message" => "Token error !"
        //     ], 400);
        // }
        $token = PersonalAccessToken::findToken($request->header('token'));
        $user = User::where('id', $token->tokenable_id)->first();
        if ($user->role != 'admin') {
            return response([
                'message' => 'Admin only route !'
            ], 403);
        }
        return $next($request);
    }
}
