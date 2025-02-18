<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private function vaildateProductInputs(Request $request, $required = "required")
    {
        // $request->validate([
        //     "name" => "$required|string",
        //     "price" => "$required|integer",
        //     "desc" => "$required|string",
        //     "category_id" => "$required|exists:categories,id",
        //     "brand_id" => "$required|exists:brands,id",
        //     "stock_quantity" => "$required|integer",
        //     "discount" => "gt:0|lt:100"
        // ]);

        $data = Validator::make($request->all(), [
            "name" => "$required|string",
            "price" => "$required|integer",
            "desc" => "$required|string",
            "category_id" => "$required|exists:categories,id",
            "brand_id" => "$required|exists:brands,id",
            "stock_quantity" => "$required|integer",
            "discount" => "gt:0|lt:100"
        ])->validate();

        return $data;
    }
    public function createProduct(Request $request)
    {
        $this->vaildateProductInputs($request);
        Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "desc" => $request->desc,
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id,
            "stock_quantity" => $request->stock_quantity,
            "discount" => $request->discount
        ]);
        return response([
            "message" => "Product created successfuly"
        ], 201);
    }
    public function updateProduct(Request $request, $id)
    {
        $newProductData = $this->vaildateProductInputs($request, false);
        $product = Product::find($id);

        if (!$product) {
            return response([
                'message' => 'Product not found'
            ], 404);
        }

        $product->update($newProductData);
        return response([
            'message' => 'Product updated successfuly'
        ], 200);
    }
}
