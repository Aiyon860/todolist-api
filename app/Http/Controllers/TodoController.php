<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use Throwable;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        Log::info('Creating todo...');

        $data = $request->validated();

        try {
            $todo = Todo::create($data);

            Log::info('Todo created successfully!');

            return response()->json([
                'success' => true,
                'message' => 'Todo created.',
                'data' => new TodoResource($todo)
            ], 201);
        } catch (Throwable $e) {
            Log::error('Failed to create todo.', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create todo.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
