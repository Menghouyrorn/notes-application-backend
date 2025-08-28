<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\TaskModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 15);
            $title= $request->query('title');

            $task= TaskModel::query()
                ->when($title, fn($query) => $query->where('title', 'like', "%{$title}%"))
                ->paginate($perPage);

            return  TaskResource::collection($task);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTaskByUser()
    {
        try {
            $user = Auth::user();
            $tasks = User::query()->where('id', $user->id)->with('tasks')->first();
            return  UserResource::make($tasks);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            $task = TaskModel::findOrFail($id);
            return TaskResource::make($task);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $task= TaskModel::create([
                'title' => $data['title'],
                'description' => $data['description'],
                "user_id" => Auth::id(),
            ]);
            return  TaskResource::make($task);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $task = TaskModel::findOrFail($id);

            $data = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string'
            ]);

            $task->update($data);

            return TaskResource::make($task);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $task = TaskModel::findOrFail($id);
            $task->delete();
            return response()->json([
                'message' => 'Task deleted successfully'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
