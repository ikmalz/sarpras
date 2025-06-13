<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view items')->only(['index']);
        $this->middleware('permission:edit items')->only(['edit']);
        $this->middleware('permission:create items')->only(['create']);
        $this->middleware('permission:delete items')->only(['destroy']);
    }
    /**
     * Display a listing of the res ource.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case '$created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default;
                $query->orderBy('created_at', 'desc');
        }

        $rowsPerPage = $request->get('rows', 5);

        $totalItem = Item::count();
        $categories = Categories::all();
        $items = $query->paginate($rowsPerPage);

        return view('items.list', [
            'items' => $items,
            'totalItem' => $totalItem,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all();
        return view('items.create', compact('categories'));
    }

    public function itemsByCategory(Request $request, $categoryId)
    {
        $rowsPerPage = $request->input('rows', 5);
        $categories = Categories::all();
        $category = Categories::find($categoryId);

        if (!$category) {
            return redirect()->route('items.index')->with('error', 'Kategori tidak ditemukan.');
        }

        $items = Item::with('category')
            ->where('category_id', $categoryId)
            ->paginate($rowsPerPage);

        $totalItem = Item::where('category_id', $categoryId)->count();

        return view('items.list', [
            'items' => $items,
            'totalItem' => $totalItem,
            'categories' => $categories,
            'category' => $category,
        ]);
    }

    public function itemsBySlug(Request $request, $slug)
    {
        $rowsPerPage = $request->input('rows', 5);
        $categories = Categories::all();
        $category = Categories::where('slug', $slug)->first();

        if (!$category) {
            return redirect()->route('items.index')->with('error', 'Kategori tidak ditemukan.');
        }

        $items = Item::with('category')
            ->where('category_id', $category->id)
            ->paginate($rowsPerPage);

        $totalItem = Item::where('category_id', $category->id)->count();

        return view('items.list', [
            'items' => $items,
            'totalItem' => $totalItem,
            'categories' => $categories,
            'category' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return redirect()->route('items.create')
                ->withInput()
                ->withErrors($validator);
        }

        $path = $request->file('image')->store('items', 'public');

        $item = new Item();
        $item->name = $request->name;
        $item->stock = $request->stock;
        $item->image_url = $path;
        $item->category_id = $request->category_id;
        $item->save();

        return redirect()->route('items.index')
            ->with('success', 'Item added successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = Categories::all();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);


        if ($validator->fails()) {
            return redirect()->route('items.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $item->image_url = $path;
        }


        $item->name = $request->name;
        $item->stock = $request->stock;
        $item->category_id = $request->category_id;
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $item = Item::find($request->id);

        if ($item == null) {
            session()->flash('error', 'Item not found');
            return response()->json(['status' => false]);
        }

        $item->delete();
        session()->flash('success', 'Item deleted successfully.');
        return response()->json(['status' => true]);
    }

    public function listByCategory($id)
    {
        $category = Categories::findOrFail($id);
        $items = Item::where('category_id', $id)->paginate(5);
        $totalItem = Item::where('category_id', $id)->count();

        return view('items.list', [
            'items' => $items,
            'category' => $category,
            'totalItem' => $totalItem
        ]);
    }
}
