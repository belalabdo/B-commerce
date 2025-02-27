<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductController;

class BrandsController extends Controller
{
    public function getAll()
    {
        $brands = Brand::all();
        return response($brands);
    }
    public function get($id)
    {
        $brand = Brand::where("id", $id)->first();
        return response($brand);
    }
    public function getProducts(Request $request, $id)
    {
        $products = Product::where("brand_id", $id);
        return ProductController::getAll($request, $products);
    }
    public function create(Request $request)
    {
        $data = Validator::make($request->all(), [
            "name" => "required|string|unique:brands",
        ])->validate();
        $brand = Brand::create($data);
        return response([
            "message" => "Brand created successfuly",
            "brand" => $brand
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(), [
            "name" => "required|string",
        ])->validate();
        $brand = Brand::where("id", $id)->first();
        if (!$brand) {
            return response([
                "message" => "Brand not found"
            ], 404);
        }
        $brand->update($data);
        return response([
            "message" => "Brand updated successfuly",
            "brand" => $brand
        ]);
    }
    public function delete($id)
    {
        $brand = Brand::where("id", $id)->first();
        if (!$brand) {
            return response([
                "message" => "Brand not found"
            ], 404);
        }
        $brand->delete();
        return response([
            "message" => "Brand deleted successfuly"
        ]);
    }
}
