<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view category')->only(['index']);
        $this->middleware('permission:edit category')->only(['edit']);
        $this->middleware('permission:create category')->only(['create']);
        $this->middleware('permission:delete category')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Categories::query();

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

        $rows = $request->get('rows', 5);
        $totalCategory = Categories::count();
        $categories = $query->paginate($rows);


        return view('categories.list', [
            'categories' => $categories,
            'totalCategories' => $totalCategory
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|min:1',
            'name' => 'required|min:1'
        ]);

        if ($validator->passes()) {

            $category = new Categories();
            $category->slug = $request->slug;
            $category->name = $request->name;
            $category->save();

            return redirect()->route('categories.index')->with('success', 'Category added successfully.');
        } else {
            return redirect()->route('categories.create')->withInput()->withErrors($validator);
        }

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->route('categories.create')->withErrors($validator)->withInput();
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Categories::findOrFail($id);
        return view('categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Categories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'slug' => 'required|min:1',
            'name' => 'required|min:1'
        ]);

        if ($validator->passes()) {

            $category->slug = $request->slug;
            $category->name = $request->name;
            $category->save();

            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } else {
            return redirect()->route('categories.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $category = Categories::find($request->id);

        if ($category == null) {
            session()->flash('error', 'Category not found');
            return response()->json([
                'status' => false
            ]);
        }

        $category->delete();
        session()->flash('success', 'Category deleted succesfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
