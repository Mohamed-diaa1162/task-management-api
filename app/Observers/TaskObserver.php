<?php

namespace App\Observers;

use App\Models\Task;

final class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        cache()->tags('tasks-' . auth('api')->id())->flush();
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        cache()->tags('tasks-' . auth('api')->id())->flush();
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        cache()->tags('tasks-' . auth('api')->id())->flush();
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        cache()->tags('tasks-' . auth('api')->id())->flush();
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        cache()->tags('tasks-' . auth('api')->id())->flush();
    }
}
