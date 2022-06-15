<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model,Prunable,SoftDeletes};

class Post extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $perPage = 5;

    protected $fillable = ['blog_id', 'subject', 'body'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subHours(3));
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            $model->load('comments');
            $model->comments()->delete();
        });

        static::restored(function ($model) {
            $model->load('comments');
            $model->comments()->restore();
        });
    }
}
