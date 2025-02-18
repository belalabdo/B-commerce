<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    static public function create($user_id)
    {
        Cart::create([
            "user_id" => $user_id
        ]);
    }
}
