<?php

namespace App\Services\V1;

use Illuminate\Http\Request;

final class CacheService
{
    public function generateTaskCacheName(Request $request): string
    {
        $userId = auth('api')->id();
        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page');
        $all = $request->has('all');

        return "tasks:user:$userId:page:$page:perPage:$perPage:all:$all";
    }


    public function generateCommentCacheName(Request $request): string
    {
        $taskId = $request->integer('task_id');
        $commentId = $request->integer('comment_id');
        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page');
        $all = $request->has('all');

        return "comments:task:$taskId:comment:$commentId:page:$page:perPage:$perPage:all:$all";
    }
}
