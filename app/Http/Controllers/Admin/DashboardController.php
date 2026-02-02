<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Counters
        $totalUsers = User::count();
        $activeJobs = Job::where('status', 'active')->count();
        $totalViews = Post::sum('views');

        // 2. Trending Posts (Top 5)
        $trendingPosts = Post::with('category')
            ->orderByDesc('views')
            ->take(5)
            ->get();

        // 3. User Growth Chart (Last 7 Days)
        $chartData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format for ApexCharts
        $dates = [];
        $counts = [];

        // Fill missing days with 0
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::now()->subDays($i)->format('d M');
            $record = $chartData->firstWhere('date', $date);
            $counts[] = $record ? $record->count : 0;
        }

        return view('dashboard', compact(
            'totalUsers',
            'activeJobs',
            'totalViews',
            'trendingPosts',
            'dates',
            'counts'
        ));
    }
}
