<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model,Prunable,SoftDeletes};

class Blog extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $fillable = ['name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subHours(3));
    }
}
