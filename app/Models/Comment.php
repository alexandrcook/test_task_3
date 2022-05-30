<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model,Prunable,SoftDeletes};

class Comment extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $fillable = ['post_id', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subHours(3));
    }
}
