<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class CompanyRepository
{
    /**
     * Cache TTL in seconds (e.g., 60 minutes)
     */
    protected $ttl = 3600;

    /**
     * Get paginated companies with filters
     */
    public function getPaginated(array $filters = [], $perPage = 12)
    {
        // Generate a unique cache key based on filters and current page
        $page = request()->get('page', 1);
        $filterHash = md5(json_encode($filters));
        $cacheKey = "companies.list.{$filterHash}.page.{$page}.perPage.{$perPage}";

        return Cache::remember($cacheKey, $this->ttl, function () use ($filters, $perPage) {
            $query = Company::withCount(['jobs' => function ($q) {
                $q->where('status', 'active');
            }]);

            // Filter: Only companies with Verified status?
            $query->verified();

            // Search by Name
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%");
            }

            // Sorting
            $sort = $filters['sort'] ?? 'newest';
            switch ($sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'most_jobs':
                    $query->orderByDesc('jobs_count');
                    break;
                case 'newest':
                default:
                    $query->orderByDesc('created_at');
                    break;
            }

            return $query->paginate($perPage);
        });
    }

    /**
     * Find company by slug
     */
    public function findBySlug($slug)
    {
        return Cache::remember("companies.slug.{$slug}", $this->ttl, function () use ($slug) {
            return Company::where('slug', $slug)
                ->verified()
                ->with(['jobs' => function ($q) {
                    $q->where('status', 'active')->latest()->take(5);
                }])
                ->withCount(['jobs' => function ($q) {
                    $q->where('status', 'active');
                }])
                ->firstOrFail();
        });
    }
}
