<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {
            $per_page = $request->query('per_page', 10);
            $roles = RoleModel::paginate($per_page);
            return RoleResource::collection($roles);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validation = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);

            $roles = RoleModel::create($validation);
            return new RoleResource($roles);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(int $id,Request $request)
    {
        try {
            $roles = RoleModel::query()->when($id, fn($q) => $q->where('id', $id))->first();
            if (!$roles) {
                return response()->json(['error' => 'Role not found'], 404);
            }
            $roles->update($request->only(['name']));
            return new RoleResource($roles);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(int $id)
    {
        try {
            $roles = RoleModel::query()->when($id, fn($q) => $q->where('id', $id))->first();
            if (!$roles) {
                return response()->json(['error' => 'Role not found'], 404);
            }
            $roles->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
