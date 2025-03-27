<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};

final class Comment extends Model
{
    protected static function booted()
    {
        static::creating(function (self $self) {
            $self->user_id = $self->user_id ?? auth('api')->id();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function comments(): HasMany
    {
        return $this->belongsTo(Comment::class)->with('comments');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('comment_id');
    }
}
