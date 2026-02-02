<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'job_vacancies';

    protected $fillable = [
        'title',
        'slug',
        'company_id',
        'location',
        'salary_range',
        'type',
        'status',
        'description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get job applications
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get applications count
     */
    public function applicationsCount(): int
    {
        return $this->applications()->count();
    }
}
