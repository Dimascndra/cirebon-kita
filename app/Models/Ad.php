<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'url',
        'placement',
        'start_date',
        'end_date',
        'is_active',
        'clicks',
        'impressions',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'clicks' => 'integer',
        'impressions' => 'integer',
    ];

    /**
     * Scope: Get active ads by placement
     */
    public function scopeActive($query, $placement = null)
    {
        $now = now();

        $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
            });

        if ($placement) {
            $query->where('placement', $placement);
        }

        return $query;
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/media/misc/placeholder-image.jpg');
    }

    /**
     * Get CTR (Click Through Rate)
     */
    public function getCtrAttribute()
    {
        if ($this->impressions == 0) {
            return 0;
        }
        return round(($this->clicks / $this->impressions) * 100, 2);
    }

    /**
     * Check if ad is currently active based on schedule
     */
    public function isScheduledActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }
}
