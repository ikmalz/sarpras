<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryApiController extends Controller
{
    public function index()
    {
        $categories = Categories::with('items')->get();

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Categories::with('items')->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string',
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Categories::create([
            'slug' => $request->slug,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category Not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'slug' => 'required|string',
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category->update([
            'slug' => $request->slug,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    public function destroy($id){
        $category = Categories::find($id);

        if(!$category) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.'
        ]);
    }
}
