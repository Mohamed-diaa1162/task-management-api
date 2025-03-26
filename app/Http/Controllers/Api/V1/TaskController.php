<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Task\{CreateTaskAction, UpdateTaskAction};
use App\Http\Resources\Api\V1\{TaskResource, TasksResource};
use App\Http\Requests\Api\V1\{StoreTaskRequest, UpdateTaskRequest};

final class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::query()
            ->where(
                fn($query) => $query
                    ->where('user_id', auth('api')->id())
                    ->orWhereHas('users', fn($query) => $query->where('user_id', auth('api')->id()))
            )
            ->paginateOrAll();

        return TasksResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = (new CreateTaskAction(
            data: $request->safe()->except('assigned_to'),
            users: $request->array('assigned_to')
        ))
        ->execute();

        return jsonResponse(data: new TaskResource($task), message: 'Task updated successfully', status: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        abort_if(authUser('api')->cannot('view', $task), 403, 'This action is unauthorized.');

        $task->load(['owner', 'users']);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        $task = (new UpdateTaskAction(
            task: $task,
            data: $request->safe()->except('assigned_to'),
            users: $request->array('assigned_to')
        ))
        ->execute();

        return jsonResponse(data: new TaskResource($task), message: 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        abort_if(authUser('api')->cannot('delete', $task), 403, 'This action is unauthorized.');

        $task->delete();

        return jsonResponse(message: 'Task deleted successfully');
    }
}
