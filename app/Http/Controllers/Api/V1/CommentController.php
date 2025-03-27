<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CommentResource;
use App\Http\Requests\Api\V1\{StoreCommentRequest, UpdateCommentRequest};

final class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $comments = Comment::query()
            ->with('user', 'comments')
            ->root()
            ->when($request->has('task_id'), fn($query) => $query->where('task_id', $request->task_id))
            ->when($request->has('comment_id'), fn($query) => $query->where('comment_id', $request->comment_id))
            ->paginateOrAll();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->validated());

        return jsonResponse(data: new CommentResource($comment->load('user')), message: 'Comment created successfully', status: 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return jsonResponse(data: new CommentResource($comment), message: 'Comment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        abort_if(authUser('api')->cannot('delete', $comment), 403, 'This action is unauthorized.');

        $comment->delete();

        return jsonResponse(message: 'Comment deleted successfully');
    }
}
