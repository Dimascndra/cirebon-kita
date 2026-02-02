<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::published()->latest('updated_at')->get();
        $jobs = Job::active()->latest('updated_at')->get();

        return Response::view('seo.sitemap', [
            'posts' => $posts,
            'jobs' => $jobs,
        ])->header('Content-Type', 'text/xml');
    }
}
