<?php

namespace App\Repositories;

use App\Models\Job;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class JobRepository
{
    protected $ttl = 3600;

    public function getLatest($limit = 5)
    {
        return Cache::remember("jobs.latest.{$limit}", $this->ttl, function () use ($limit) {
            return Job::active()
                ->with('company')
                ->latest('created_at')
                ->take($limit)
                ->get();
        });
    }

    public function getPaginated(array $filters = [], $perPage = 9)
    {
        $page = request()->get('page', 1);
        $filterHash = md5(json_encode($filters));
        $cacheKey = "jobs.list.{$filterHash}.page.{$page}.perPage.{$perPage}";

        return Cache::remember($cacheKey, 600, function () use ($filters, $perPage) {
            $query = Job::active()->with('company');

            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function (Builder $q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // Add other filters if needed (location, type, etc.)
            if (!empty($filters['location'])) {
                $query->where('location', 'like', "%{$filters['location']}%");
            }

            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }

            return $query->latest('created_at')->paginate($perPage);
        });
    }

    public function getFilters()
    {
        return Cache::remember('jobs.filters', $this->ttl, function () {
            $locations = Job::active()->select('location')->distinct()->pluck('location');
            $types = Job::active()->select('type')->distinct()->pluck('type');
            return compact('locations', 'types');
        });
    }

    public function getRelated($excludeId, $type, $location, $limit = 4)
    {
        return Cache::remember("jobs.related.{$excludeId}.limit.{$limit}", $this->ttl, function () use ($excludeId, $type, $location, $limit) {
            return Job::active()
                ->where('id', '!=', $excludeId)
                ->where(function ($q) use ($type, $location) {
                    $q->where('type', $type)
                        ->orWhere('location', $location);
                })
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    public function findBySlug($slug)

    {
        return Cache::remember("jobs.slug.{$slug}", $this->ttl, function () use ($slug) {
            return Job::active()->where('slug', $slug)->with('company')->firstOrFail();
        });
    }
}
