<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Redirect user to their dashboard based on role
        if ($user->hasRole('SuperAdmin') || $user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('Company')) {
            return redirect()->route('company.dashboard');
        }

        return view('spa');
    }

    public function summary(Request $request)
    {
        $user = $request->user();

        $applicationsQuery = $user->jobApplications()->with('job.company');
        $applications = (clone $applicationsQuery)
            ->latest('applied_at')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'stats' => [
                    'applications_count' => (clone $applicationsQuery)->count(),
                    'reviewing_count' => (clone $applicationsQuery)->whereIn('status', ['reviewing', 'shortlisted'])->count(),
                    'accepted_count' => (clone $applicationsQuery)->where('status', 'accepted')->count(),
                    'bookmarked_news_count' => Post::published()->count(),
                ],
                'recent_applications' => $applications,
            ],
        ]);
    }
}
