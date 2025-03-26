<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Actions\Action;
use App\Jobs\V1\TaskNotifyUserJob;

final class CreateTaskAction implements Action
{
    public function __construct(private array $data = [], private array $users = [])
    {
    }
    /**
     * Execute the action.
     *
     * @return Task
     */
    public function execute(): Task
    {
        $task = Task::create($this->data);

        if (count($this->users)) $task->users()->sync($this->users);

        TaskNotifyUserJob::dispatch($task, $this->users);

        return $task;
    }
}
