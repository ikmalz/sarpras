<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemApiController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();

        return response()->json([
            'status' => true,
            'data' => $items
        ]);
    }

    public function show($id)
    {
        $item = Item::with('category')->find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Items not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $item
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = $request->file('image')->store('items','public');

        $item = Item::create([
            'name' => $request->name,
             'stock' => $request->stock,
             'image_url' => $imagePath,
             'category_id' => $request->category_id 
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Item created successfully.',
            'data' => $item
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string',
            'stock' => 'sometimes|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if($request->hasFile('image')) {
            if($item->image_url && Storage::disk('public')->exists($item->image_url)) {
                Storage::disk('public')->delete($item->image_url);
            }    

            $imagePath = $request->file('image')->store('items','public');
            $item->image_url = $imagePath;
        }

        $item->update($request->only(['name','stock','category_id']));
        $item->save();

        return response()->json([
            'status' => true,
            'message' => 'Item updated successfully.',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item deleted successfully.'
        ]);
    }
}
