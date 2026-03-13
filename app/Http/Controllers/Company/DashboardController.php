<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('spa');
    }

    public function summary()
    {
        // TODO: Filter by company when Company model is ready
        // For now, get all jobs for demo

        // Statistics
        $totalJobs = Job::count();
        $activeJobs = Job::where('status', 'active')->count();
        $totalApplications = JobApplication::count();

        // Application status breakdown
        $pendingApplications = JobApplication::where('status', 'pending')->count();
        $reviewingApplications = JobApplication::where('status', 'reviewing')->count();
        $shortlistedApplications = JobApplication::where('status', 'shortlisted')->count();
        $acceptedApplications = JobApplication::where('status', 'accepted')->count();
        $rejectedApplications = JobApplication::where('status', 'rejected')->count();

        // Recent applications
        $recentApplications = JobApplication::with(['user', 'job'])
            ->latest('applied_at')
            ->take(5)
            ->get();

        // Jobs with most applications
        $popularJobs = Job::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();

        // Application trend (last 7 days)
        $dates = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('d M');
            $counts[] = JobApplication::whereDate('applied_at', $date)->count();
        }

        return response()->json([
            'success' => true,
            'data' => compact(
                'totalJobs',
                'activeJobs',
                'totalApplications',
                'pendingApplications',
                'reviewingApplications',
                'shortlistedApplications',
                'acceptedApplications',
                'rejectedApplications',
                'recentApplications',
                'popularJobs',
                'dates',
                'counts'
            ),
        ]);
    }
}
