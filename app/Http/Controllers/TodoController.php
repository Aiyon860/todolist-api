<?php

namespace App\Http\Controllers;

use App\Exports\TodosExport;
use App\Http\Requests\GetTodoRequest;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetTodoRequest $request)
    {
        $filters = $request->validated();

        if ($request->filled('format') && $request->query('format') === 'excel') {
            try {
                Log::info("Generating Todolist Report in Excel...");

                return Excel::download(
                    new TodosExport($filters),
                    'todos-report-' . now()->format('Y-m-d') . '.xlsx'
                );
            } catch (Throwable $e) {
                Log::error('Failed to generate Todolist Report.', [
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate Todolist Report.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        try {
            Log::info("Fetch todos...");

            $todos = Todo::filtered($filters)->paginate(10);

            return TodoResource::collection($todos)
                ->additional([
                    'success' => true,
                    'message' => 'Todos retrived.'
                ])
        } catch (Throwable $e) {
            Log::error('Failed to fetch todos.', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch todos.',
                'error' => $e->getMessage()
            ], 500);
        }
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
