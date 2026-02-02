<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\JobRepository;
use App\Repositories\AdRepository;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    protected $postRepository;
    protected $jobRepository;
    protected $adRepository;

    public function __construct(
        PostRepository $postRepository,
        JobRepository $jobRepository,
        AdRepository $adRepository
    ) {
        $this->postRepository = $postRepository;
        $this->jobRepository = $jobRepository;
        $this->adRepository = $adRepository;
    }

    public function getHero()
    {
        return $this->postRepository->getHero();
    }

    public function getLatestNews()
    {
        return $this->postRepository->getLatest(6);
    }

    public function getLatestJobs()
    {
        return $this->jobRepository->getLatest(5);
    }

    public function getCategories()
    {
        return Cache::remember('home.categories', 3600, function () {
            return Category::withCount(['posts', 'jobs'])->get();
        });
    }

    public function getFeaturedCompanies()
    {
        return Cache::remember('home.featured_companies', 3600, function () {
            return Company::verified()
                ->inRandomOrder()
                ->take(8)
                ->get();
        });
    }

    public function getBanners()
    {
        $ads = $this->adRepository->getActive();

        // Track impressions and format data
        $formattedAds = $ads->map(function ($ad) {
            // Track impression
            // Ideally should be queued, but direct increment is fine for small scale
            // NOTE: We cannot easily cache the *increment* operation if we cache the *result* of this method.
            // If getBanners() result is cached, impressions won't increment.
            // However, the Repository caches the DB query for *fetching* ads.
            // Here we iterate over the cached ads collection.

            // Side-effect: Incrementing impression.
            // This will happen on every request even if ads are fetched from cache.
            $ad->increment('impressions');

            return [
                'id' => $ad->id,
                'title' => $ad->title,
                'image' => $ad->image,
                'link' => route('ad.click', $ad->id),
                'position' => $ad->placement,
            ];
        });

        return [
            'top' => $formattedAds->where('position', 'header')->values(),
            'sidebar' => $formattedAds->where('position', 'sidebar')->values(),
            'homepage' => $formattedAds->where('position', 'homepage')->values(),
            'footer' => $formattedAds->where('position', 'footer')->values(),
        ];
    }
}
