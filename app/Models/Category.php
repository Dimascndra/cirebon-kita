<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
