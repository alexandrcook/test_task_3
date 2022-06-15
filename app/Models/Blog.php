<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model,Prunable,SoftDeletes};

class Blog extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $fillable = ['name', 'user_id'];

    protected $perPage = 10;

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

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            $model->load('posts');
            $nestedPosts = $model->posts();
            $nestedPosts->each(fn($item) => $item->delete());
        });

        static::restored(function ($model) {
            $model->load('posts');
            $nestedPosts = $model->posts();
            $nestedPosts->each(fn($item) => $item->restore());
        });
    }
}
