<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Task;
use App\Services\TaskService;
use Psy\Readline\Hoa\Console;

class TasksController extends Controller
{
     protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        try {
            $tasks = $this->taskService->getAllTasks();
            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $task = $this->taskService->createTask($request->all());
            return response()->json($task, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validacion Error.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $task = $this->taskService->findTaskById($id);
            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'task not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $task = $this->taskService->updateTask($id, $request->all());
            return response()->json($task, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validacion Error.',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'task not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }
    public function tasksByUser(string $userId)
    {
        try {
            $tasks = $this->taskService->findTaskByUserId($userId);
            return response()->json($tasks);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }

    public function tasksByStatus(string $status)
    {
        try {
            $tasks = $this->taskService->findTaskByStatus($status);
            return response()->json($tasks);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }


    public function destroy(string $id)
    {
        try {
            $this->taskService->deleteTask($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'task not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error, something is wrong in request.'], 500);
        }
    }
}
