<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistsController extends Controller
{
    static public function create($user_id)
    {
        Wishlist::create([
            "user_id" => $user_id
        ]);
    }
    public function getWishlist(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->header("token"));
        $userId = $token->tokenable_id;
        return Wishlist::where("user_id", $userId)->first();
    }
    public function add(Request $request)
    {
        $wishlist = $this->getWishlist($request);

        if (!$product = Product::find($request->product_id))
            return response(["message" => "Product not found"], 404);

        if (count($wishlist->wishlistItems->where("product_id", $request->product_id)))
            return response(["message" => "Product already in your wishlist"], 401);

        WishlistItem::create([
            "wishlist_id" => $wishlist->id,
            "product_id" => $request->product_id
        ]);

        return response(["message" => "Product added to your wishlist successfuly"]);
    }
    public function get(Request $request)
    {
        $wishlist = $this->getWishlist($request);

        $wishlist->wishlistItems = $wishlist->wishlistItems->map(function (WishlistItem $wishlistItem) {

            $original = Product::find($wishlistItem->product_id);

            return [
                "name" => $original->name,
                "category" => $original->category,
                "brand" => $original->brand,
            ];
        });
        return response($wishlist->wishlistItems);
    }
    public function delete(Request $request)
    {
        $wishlist = $this->getWishlist($request);

        if (!$wishlistItem = $wishlist->wishlistItems->where("product_id", $request->product_id)->first())
            return response(["message" => "Product isn't in the wishlist"], 404);

        $wishlistItem->delete();
        return response(["message" => "Product removed successfuly"]);
    }
}
