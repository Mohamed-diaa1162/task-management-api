<?php

namespace App\Observers;

use App\Models\Comment;

final class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        cache()->tags('comments-'. $comment->task_id)->flush();
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        cache()->tags('comments-'. $comment->task_id)->flush();
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        cache()->tags('comments-'. $comment->task_id)->flush();
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        cache()->tags('comments-'. $comment->task_id)->flush();
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        cache()->tags('comments-'. $comment->task_id)->flush();
    }
}
