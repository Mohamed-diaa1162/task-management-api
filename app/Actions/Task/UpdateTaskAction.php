<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Actions\Action;
use App\Jobs\V1\TaskNotifyUserJob;

final class UpdateTaskAction implements Action
{
    public function __construct(private Task $task ,private array $data = [], private array $users = [])
    {
    }
    /**
     * Execute the action.
     *
     * @return Task
     */
    public function execute(): Task
    {
        $this->task->update($this->data);

        $new_users = $this->task->users()->pluck('user_id')->diff($this->users);

        if (count($this->users)) $this->task->users()->sync($this->users);

        TaskNotifyUserJob::dispatch($this->task, $new_users);

        return $this->task;
    }
}
