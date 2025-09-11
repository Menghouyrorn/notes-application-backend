<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\TaskModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 15);
            $title = $request->query('title');

            $task = TaskModel::query()
                ->when($title, fn($query) => $query->where('title', 'like', "%{$title}%"))
                ->paginate($perPage);

            return TaskResource::collection($task);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTaskByUser(Request $request)
    {
        try {
            $user = Auth::user();
            $title = $request->query('title');
            $find_today = filter_var($request->query('find_today', false));
            $find_week = filter_var($request->query('find_week', false));
            $tasks = User::query()->where('id', $user->id)->
            with(['tasks' => function ($q) use ($find_today, $find_week, $title,) {
                $q->orderBy('created_at', 'desc');
                if ($title) {
                    $q->where(DB::raw('UPPER(title)'), 'like', '%' . strtoupper($title) . '%');
                }
                if ($find_today) {
                    $q->whereDate('created_at', now()->toDateString());
                }
                if ($find_week) {
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                }
            }])->first();
            return UserResource::make($tasks);
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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

            $task = TaskModel::create([
                'title' => $data['title'],
                'description' => $data['description'],
                "user_id" => Auth::id(),
            ]);
            return TaskResource::make($task);
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
