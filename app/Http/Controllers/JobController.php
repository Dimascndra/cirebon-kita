<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\JobService;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function index()
    {
        // Get unique locations and types for filter dropdowns
        $filters = $this->jobService->getFilters();
        $locations = $filters['locations'];
        $types = $filters['types'];

        return view('jobs.index', compact('locations', 'types'));
    }

    public function list(Request $request)
    {
        $filters = $request->only(['search', 'location', 'type']);
        $paginated = $this->jobService->getJobs($filters);

        return response()->json([
            'success' => true,
            'message' => 'Jobs fetched successfully',
            'data' => $paginated
        ]);
    }

    public function detail($slug)
    {
        $data = $this->jobService->getJobBySlug($slug);
        $job = $data['job'];
        return view('jobs.show', compact('job', 'slug'));
    }

    public function show($slug)
    {
        try {
            $data = $this->jobService->getJobBySlug($slug);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found'
            ], 404);
        }
    }
}
