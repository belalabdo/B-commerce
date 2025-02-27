<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProductController;

class CategoriesController extends Controller
{
    public function getAll()
    {
        $categories = Category::all();
        return response($categories);
    }
    public function get($id)
    {
        $category = Category::where("id", $id)->first();
        return response($category);
    }
    public function getProducts(Request $request, $id)
    {
        $products = Product::where("category_id", $id);
        return ProductController::getAll($request, $products);
    }
    public function create(Request $request)
    {
        $data = Validator::make($request->all(), [
            "name" => "required|string|unique:categories",
        ])->validate();
        $category = Category::create($data);
        return response([
            "message" => "Category created successfuly",
            "category" => $category
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = Validator::make($request->all(), [
            "name" => "required|string",
        ])->validate();
        $category = Category::where("id", $id)->first();
        if (!$category) {
            return response([
                "message" => "Category not found"
            ], 404);
        }
        $category->update($data);
        return response([
            "message" => "Category updated successfuly",
            "category" => $category
        ]);
    }
    public function delete($id)
    {
        $category = Category::where("id", $id)->first();
        if (!$category) {
            return response([
                "message" => "Category not found"
            ], 404);
        }
        $category->delete();
        return response([
            "message" => "Category deleted successfuly"
        ]);
    }
}
