<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PersonalAccessToken;
use App\Models\Product;
use Illuminate\Http\Request;

class CartsController extends Controller
{
    static public function create($user_id)
    {
        Cart::create([
            "user_id" => $user_id
        ]);
    }
    public function getCart(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->header("token"));
        $userId = $token->tokenable_id;
        return Cart::where("user_id", $userId)->first();
    }
    public function add(Request $request)
    {
        $cart = $this->getCart($request);

        if (!$product = Product::find($request->product_id))
            return response(["message" => "Product not found"], 404);

        if (count($cart->cartItems->where("product_id", $request->product_id)))
            return response(["message" => "Product already in your cart"], 401);

        CartItem::create([
            "cart_id" => $cart->id,
            "product_id" => $request->product_id,
            "price_at_time" => $product->price
        ]);

        return response(["message" => "Product added to your cart successfuly"]);
    }
    public function get(Request $request)
    {
        $cart = $this->getCart($request);

        $cart->cartItems = $cart->cartItems->map(function (CartItem $cartItem) {

            $original = Product::find($cartItem->product_id);

            return [
                "name" => $original->name,
                "price_at_time" => $cartItem->price_at_time,
                "category" => $original->category,
                "brand" => $original->brand,
                "quantity" => $cartItem->quantity,
            ];
        });
        return response($cart->cartItems);
    }
    public function update(Request $request)
    {
        $cart = $this->getCart($request);

        if (!$cartItem = $cart->cartItems->where("product_id", $request->product_id)->first())
            return response(["message" => "Product isn't in the cart"], 404);

        if ($cartItem->quantity == 1 && $request->count <= -1)
            $cartItem->delete();
        else
            $cartItem->update(["quantity" => $cartItem->quantity += $request->count]);

        return $this->get($request);
    }
    public function delete(Request $request)
    {
        $cart = $this->getCart($request);

        if (!$cartItem = $cart->cartItems->where("product_id", $request->product_id)->first())
            return response(["message" => "Product isn't in the cart"], 404);

        $cartItem->delete();
        return response(["message" => "Product removed successfuly"]);
    }
}
