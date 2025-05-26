<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view permissions')->only(['index']);
        $this->middleware('permission:edit permissions')->only(['edit']);
        $this->middleware('permission:create permissions')->only(['create']);
        $this->middleware('permission:delete permissions')->only(['destroy']);
    }

    // This method will show permissions page
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        switch ($request->sort) {
            case 'name_asc' : $query->orderBy('name', 'asc'); break;
            case 'name_desc' : $query->orderBy('name', 'desc'); break;
            case 'created_asc' : $query->orderBy('created_at', 'asc'); break;
            case '$created_desc' : $query->orderBy('created_at', 'desc'); break;
            default;
            $query->orderBy('created_at', 'desc');
        }

        $rowsPerPage = $request->get('rows', 5);
        $totalPermissions = Permission::count();
        $permissions = $query->paginate($rowsPerPage);

        return view('permissions.list', compact('permissions', 'totalPermissions'));
    }

    // This method will show create permission page
    public function create()
    {
        return view('permissions.create');
    }

    // This method will insert a permission in DB
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $permission = Permission::create(['name' => $request->name]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $permission->id]);
        }

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    // This method will show edit permission page
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return response()->json($permission);
    }

    // This method will update a permission
    public function update($id, Request $request)
    {
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id'
        ]);

        if ($validator->passes()) {

            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('permissions.index')->with('success', 'permission updated successfully.');
        } else {
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    // This method will delete a permission in DB
    public function destroy(Request $request)
    {
        $id = $request->id;

        $permission = Permission::find($id);

        if ($permission == null) {
            session()->flash('error', 'Permission not found');
            return response()->json([
                'status' => false
            ]);
        }

        $permission->delete();

        session()->flash('success', 'Permission deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
