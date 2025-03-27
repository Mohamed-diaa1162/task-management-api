<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\TaskStatusEnum::class,
        ];
    }

    protected static function booted()
    {
        static::creating(function (self $self) {
            $self->user_id = $self->user_id ?? auth('api')->id();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
