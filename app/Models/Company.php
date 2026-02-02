<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'website',
        'verified',
        'description',
        'industry',
        'address',
        'email',
        'phone',
    ];

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
