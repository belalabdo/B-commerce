<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\get;

class ProductController extends Controller
{
    private function vaildateProductInputs(Request $request, $required = "required")
    {
        $data = Validator::make($request->all(), [
            "name" => "$required|string",
            "price" => "$required|decimal:0,2",
            "desc" => "$required|string",
            "category_id" => "$required|exists:categories,id",
            "brand_id" => "$required|exists:brands,id",
            "stock_quantity" => "$required|integer",
            "discount" => "gt:0|lt:100"
        ])->validate();
        return $data;
    }
    public function create(Request $request)
    {
        $this->vaildateProductInputs($request);
        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "desc" => $request->desc,
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id,
            "stock_quantity" => $request->stock_quantity,
            "discount" => $request->discount
        ]);
        return response([
            "message" => "Product created successfuly",
            "product" => $product
        ], 201);
    }
    public function update(Request $request, $id)
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
            'message' => 'Product updated successfuly',
            "product" => $product
        ], 200);
    }
    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response([
                'message' => 'Product not found'
            ], 404);
        }
        $product->delete();
        return response([
            'message' => 'Product deleted successfuly'
        ], 200);
    }
    public function get($id)
    {
        $product = Product::where("id", $id)->withAvg("ratings", 'rate')->first();
        if ($product == null) {
            return response([
                "message" => "Product not found"
            ], 404);
        }
        return response([
            "id" => $product->id,
            "name" => $product->name,
            "desc" => $product->desc,
            "price" => $product->price,
            "category" => $product->category,
            "brand" => $product->brand,
            "stock_quantity" => $product->stock_quantity,
            "rate" => $product->ratings_avg_rate,
            "discount" => $product->discount
        ]);
    }

    public function getAll(Request $request)
    {
        $products = Product::select()->withAvg("ratings", 'rate');
        if ($request->has('search')) {
            $products->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('desc', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('min_discount')) {
            $products->where('discount', '>', $request->min_discount);
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $products->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        if ($request->has('in_stock')) {
            $products->where('stock_quantity', '>', 0);
        }

        if ($request->has('rating')) {
            $rating = $request->rating;
            $products->having('ratings_avg_rate', '>', $rating - 1)
                ->having('ratings_avg_rate', '<', $rating + 1);
        }

        $products = $products->get()->map(function (Product $product) {
            return [
                "id" => $product->id,
                "name" => $product->name,
                "desc" => $product->desc,
                "price" => $product->price,
                "category" => $product->category,
                "brand" => $product->brand,
                "stock_quantity" => $product->stock_quantity,
                "average_rating" => $product->ratings_avg_rate,
                "discount" => $product->discount
            ];
        });

        return response($products);
    }
}
