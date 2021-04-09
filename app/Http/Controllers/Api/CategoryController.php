<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Auth;
use Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'adminRoleChecker'], ['only' => ['store', 'destroy']]);
    }

    public function index()
    {
        $categories = Category::with('services')->where('status', 'active')->get();

        return response([
            'status' => 'done',
            'categories' => $categories
        ], 200);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:4|unique:categories'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = new Category();
        $category->name = request('name');
        $category->slug = Str::slug(request('name'));
        $category->save();

        if ($category) {
            $category->services;
        }

        return response([
            'status' => 'done',
            'message' => 'Category added successfully',
            'category' => $category
        ], 201);
    }

    public function show($slug)
    {
        $category = Category::with('services')->where('slug', $slug)->first();

        if ($category !== null) {
            return response([
                'status' => 'done',
                'category' => $category
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'message' => 'There is no such a category like this name...'
            ], 404);
        }
    }

    public function destroy($slug)
    {
        $category = Category::with('services')->where('slug', $slug)->first();

        if ($category !== null) {
            $category->services->each(function ($item) {
                $item->delete();
            });

            $category->delete();

            return response([
                'status' => 'done',
                'message' => 'Category deleted successfully'
            ], 202);
        } else {
            return response([
                'status' => 'error',
                'message' => 'There is no such a category like this name...'
            ], 404);
        }
    }
}
