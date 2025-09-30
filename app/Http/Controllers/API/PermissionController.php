<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionsResource;
use App\Models\PermissionsModel;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        try {
                $per_page = $request->get('per_page', 10);
                $permissions = PermissionsModel::paginate($per_page);
                return  PermissionsResource::collection($permissions);
        }catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        try {
            $validate = request()->validate([
                'name' => 'required|string|unique:permissions,name',
            ]);
            $permission = PermissionsModel::create($validate);
            return new PermissionsResource($permission);
        }catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id,Request $request)
    {
        try {
            $permission = PermissionsModel::find($id);
            if (!$permission) {
                return response()->json(['message' => 'Permission not found'], 404);
            }
            $permission->update($request->all());
            return new PermissionsResource($permission);
        }catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(int $id)
    {
        try {
            $permission = PermissionsModel::find($id);
            if (!$permission) {
                return response()->json(['message' => 'Permission not found'], 404);
            }
            $permission->delete();
            return response()->json(['message' => 'Permission deleted successfully'], 200);
        }catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
