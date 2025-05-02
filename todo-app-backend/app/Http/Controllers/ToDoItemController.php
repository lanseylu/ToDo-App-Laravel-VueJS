<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreToDoItemRequest;
use App\Http\Requests\UpdateToDoItemRequest;
use Illuminate\Http\Request;
use App\Models\ToDoItem;
use App\Http\Resources\ToDoItemResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ToDoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $todos = $request->user()->toDoItems()->latest()->get();

        return ToDoItemResource::collection($todos);
    }   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreToDoItemRequest $request): JsonResource | JsonResponse
    {
        try
        {
            $user = $request->user();
            $todo = $user->toDoItems()->create([
                'title' => $request->title,
            ]);

            return new ToDoItemResource($todo);
        } 
        catch (\Illuminate\Validation\ValidationException $e) 
        {
            return response()->json(['errors' => $e->errors()], 422);
        }
        catch (\Exception $e) 
        {
            Log::error('ToDoItem Add failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unexpected error while creating new item.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ToDoItem $toDoItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateToDoItemRequest $request, $id): JsonResource | JsonResponse
    {
        try 
        {
            $user = $request->user();
            
            $todo = $user->toDoItems()->findOrFail($id);
            $todo->update($request->validated());

            return new ToDoItemResource($todo);
        } 
        catch (\Illuminate\Validation\ValidationException $e) 
        {
            return response()->json(['errors' => $e->errors()], 422);
        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json([
                'message' => 'To-do item not found'
            ], 404);
        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'message' => 'Unexpected error while updating.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try 
        {
            $user = $request->user();
            
            $todo = $user->toDoItems()->findOrFail($id);
            $todo->delete();

            return response()->json([
                'message' => 'To Do Item Deleted Successfully',
            ], 200);
        } 
        catch (ModelNotFoundException $e) 
        {
            return response()->json([
                'message' => 'To-do item not found',
                'error' => $e->getMessage()
            ], 404);
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'message' => 'Unexpected error while deleting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

 
}
