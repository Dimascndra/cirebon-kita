<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
        $this->middleware('permission:news-list|news-create|news-edit|news-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:news-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:news-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:news-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('spa');
    }

    public function getData()
    {
        $posts = $this->newsService->getAll(-1);

        return response()->json([
            'data' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'category' => $post->category ? $post->category->name : '-',
                    'image' => $post->image ? asset('storage/' . $post->image) : asset('assets/images/placeholder.jpg'),
                    'status' => ucfirst($post->status),
                    'views' => $post->views,
                    'published_at' => $post->published_at ? $post->published_at->format('d M Y H:i') : '-',
                    'actions' => '
                        <a href="' . route('admin.news.edit', $post->id) . '" class="btn btn-sm btn-clean btn-icon" title="Edit">
                            <i class="la la-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-clean btn-icon" title="Delete" onclick="deleteNews(' . $post->id . ')">
                            <i class="la la-trash"></i>
                        </button>
                    '
                ];
            })
        ]);
    }

    public function create()
    {
        return view('spa');
    }

    public function show($id)
    {
        return view('spa');
    }

    public function meta()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'categories' => Category::all(['id', 'name']),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->newsService->create($request->all());
            return response()->json(['message' => 'News created successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $post = \App\Models\Post::findOrFail($id);
        return view('spa');
    }

    public function detail($id)
    {
        $post = \App\Models\Post::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->newsService->update($id, $request->all());
            return response()->json(['message' => 'News updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->newsService->delete($id);
            return response()->json(['message' => 'News deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
