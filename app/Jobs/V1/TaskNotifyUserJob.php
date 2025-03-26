<?php

namespace App\Jobs\V1;

use App\Models\{Task, User};
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

final class TaskNotifyUserJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Task $task, private $users = [])
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereIn('id',$this->users)->get();

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\V1\TaskNotification($this->task));
        }
    }
}
