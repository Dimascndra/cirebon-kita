<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class PostRepository
{
    /**
     * Cache TTL in seconds (e.g., 60 minutes)
     */
    protected $ttl = 3600;

    /**
     * Get the hero post (latest important published post)
     */
    public function getHero()
    {
        return Cache::remember('posts.hero', $this->ttl, function () {
            // Priority could be added here if we had a 'is_featured' flag,
            // but based on HomeService it's just latest published.
            return Post::published()
                ->latest('published_at')
                ->first();
        });
    }

    /**
     * Get latest published posts
     */
    public function getLatest($limit = 6)
    {
        return Cache::remember("posts.latest.{$limit}", $this->ttl, function () use ($limit) {
            return Post::published()
                ->latest('published_at')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get paginated posts with filters
     */
    public function getPaginated(array $filters = [], $perPage = 9)
    {
        // Generate a unique cache key based on filters and current page
        $page = request()->get('page', 1);
        $filterHash = md5(json_encode($filters));
        $cacheKey = "posts.list.{$filterHash}.page.{$page}.perPage.{$perPage}";

        // We only cache if it's a "standard" view (optional strategy), but user asked to use Cache.
        // Caching everything with filters requires shorter TTL or good invalidation.
        // Let's use a shorter TTL for lists (e.g., 10 mins).

        return Cache::remember($cacheKey, 600, function () use ($filters, $perPage) {
            $query = Post::published()->with('category');

            // Search
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function (Builder $q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }

            // Category Filter
            if (!empty($filters['category'])) {
                $categorySlug = $filters['category'];
                $query->whereHas('category', function (Builder $q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            // Sorting
            $sort = $filters['sort'] ?? 'newest';
            switch ($sort) {
                case 'popular':
                    $query->orderByDesc('views');
                    break;
                case 'oldest':
                    $query->orderBy('published_at');
                    break;
                case 'newest':
                default:
                    $query->orderByDesc('published_at');
                    break;
            }

            return $query->paginate($perPage);
        });
    }

    /**
     * Find post by slug
     */
    public function findBySlug($slug)
    {
        return Cache::remember("posts.slug.{$slug}", $this->ttl, function () use ($slug) {
            return Post::published()->where('slug', $slug)->with('category')->firstOrFail();
        });
    }

    /**
     * Get related posts
     */
    public function getRelated($categoryId, $excludeId, $limit = 3)
    {
        return Cache::remember("posts.related.{$categoryId}.exclude.{$excludeId}.limit.{$limit}", $this->ttl, function () use ($categoryId, $excludeId, $limit) {
            return Post::published()
                ->where('category_id', $categoryId)
                ->where('id', '!=', $excludeId)
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get trending posts
     */
    public function getTrending($limit = 5)
    {
        return Cache::remember("posts.trending.{$limit}", $this->ttl, function () use ($limit) {
            return Post::published()
                ->orderByDesc('views')
                ->take($limit)
                ->get();
        });
    }
}
