<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use App\Models\Category;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    // Page View
    public function index()
    {
        $categories = $this->newsService->getCategories(); // Feteched via service (cached)
        return view('news.index', compact('categories'));
    }

    // API Endpoint
    public function list(Request $request)
    {
        $filters = $request->only(['search', 'category', 'sort']);
        $paginated = $this->newsService->getPosts($filters);

        return response()->json([
            'success' => true,
            'message' => 'News fetched successfully',
            'data' => $paginated
        ]);
    }

    // Detail View
    public function detail($slug)
    {
        $data = $this->newsService->getPostBySlug($slug);
        $post = $data['post'];
        return view('news.show', compact('post', 'slug'));
    }

    // Detail API
    public function show($slug)
    {
        try {
            $data = $this->newsService->getPostBySlug($slug);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }
    }
}
