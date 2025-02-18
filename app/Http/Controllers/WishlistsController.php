<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistsController extends Controller
{
    static public function create($user_id)
    {
        Wishlist::create([
            "user_id" => $user_id
        ]);
    }
}
