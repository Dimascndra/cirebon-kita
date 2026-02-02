<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'cv_path',
        'status',
        'cover_letter',
        'notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    /**
     * Get the job that was applied to
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the user who applied
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge badge-light-warning">Pending</span>',
            'reviewing' => '<span class="badge badge-light-info">Reviewing</span>',
            'shortlisted' => '<span class="badge badge-light-primary">Shortlisted</span>',
            'rejected' => '<span class="badge badge-light-danger">Rejected</span>',
            'accepted' => '<span class="badge badge-light-success">Accepted</span>',
            default => '<span class="badge badge-light">Unknown</span>',
        };
    }

    /**
     * Get CV download URL
     */
    public function getCvUrlAttribute(): string
    {
        return asset('storage/' . $this->cv_path);
    }
}
