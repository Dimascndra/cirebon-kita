<?php

namespace App\Services;

use App\Models\Job;
use App\Repositories\JobRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class JobService
{
    protected $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function getJobs(array $filters)
    {
        // Use repository which handles filtering and caching
        return $this->jobRepository->getPaginated($filters, 10);
    }

    public function getJobBySlug($slug)
    {
        $job = $this->jobRepository->findBySlug($slug);

        // Related Jobs
        $related = $this->jobRepository->getRelated($job->id, $job->type, $job->location, 4);

        // Check if user has applied (Cannot be cached easily per user, do this outside or keep here unfiltered by cache)
        // Since $job is from cache, 'has_applied' attribute injection works but won't be persisted in cache for that user specifically
        // unless we clone the object or just append it safely.
        // It's safe to modify the object returned from cache for the current request context.
        $job->has_applied = false;
        if (\Illuminate\Support\Facades\Auth::check()) {
            $job->has_applied = \Illuminate\Support\Facades\Auth::user()->hasAppliedTo($job->id);
        }

        return [
            'job' => $job,
            'related' => $related
        ];
    }

    public function getFilters()
    {
        return $this->jobRepository->getFilters();
    }

    /**
     * Get all jobs for admin
     */
    public function getAll($perPage = 10)
    {
        if ($perPage == -1) {
            return Job::with('company')->latest()->get();
        }
        return Job::with('company')->latest()->paginate($perPage);
    }

    /**
     * Create new job
     */
    public function create(array $data)
    {
        // Generate Slug
        $data['slug'] = Str::slug($data['title']);

        $job = Job::create($data);

        $this->clearCache();

        return $job;
    }

    /**
     * Update job
     */
    public function update($id, array $data)
    {
        $job = Job::findOrFail($id);

        // Regenerate Slug if title changed
        if (isset($data['title']) && $data['title'] !== $job->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        $job->update($data);

        $this->clearCache();

        return $job;
    }

    /**
     * Delete job
     */
    public function delete($id)
    {
        $job = Job::findOrFail($id);
        $result = $job->delete();

        $this->clearCache();

        return $result;
    }

    protected function clearCache()
    {
        Cache::flush();
    }
}
