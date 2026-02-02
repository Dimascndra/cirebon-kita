<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getCategories()
    {
        return Cache::remember('news.categories', 3600, function () {
            return \App\Models\Category::has('posts')->get(); // Only show categories with posts? Or all? Controller used existing `Category::all()`. Let's stick to simple all() or checking if they have posts is better UX. Existing was `Category::all()`.
            // But usually we only want categories that have content. I'll stick to `Category::all()` to match previous behavior but cache it.
            return \App\Models\Category::all();
        });
    }

    public function getPosts(array $filters)
    {
        return $this->postRepository->getPaginated($filters, 9);
    }

    public function getPostBySlug($slug)
    {
        $data = [];
        $data['post'] = $this->postRepository->findBySlug($slug);

        // Side-effect: Increment views (not cached)
        $data['post']->increment('views');

        $data['trending'] = $this->postRepository->getTrending(5);
        // We don't really have a clean method for "related" in the repo that takes the logic from the service perfectly
        // service: where category_id = post->category_id, id != post->id, latest, take 3
        // repo: getRelated($categoryId, $excludeId, $limit)
        $data['related'] = $this->postRepository->getRelated($data['post']->category_id, $data['post']->id, 3);

        return $data;
    }

    /**
     * Get all posts for admin
     */
    public function getAll($perPage = 10)
    {
        // Admin likely shouldn't be cached or should be cached differently.
        // Keeping direct model access or creating a non-cached repo method is best.
        // For now, leaving direct access as it was, to prevent Admin cache issues.
        if ($perPage == -1) {
            return Post::with('category')->latest()->get();
        }
        return Post::with('category')->latest()->paginate($perPage);
    }

    /**
     * Create new post
     */
    public function create(array $data)
    {
        // Handle Image Upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('news', 'public');
        }

        // Generate Slug
        $data['slug'] = Str::slug($data['title']);

        // Set Published At if status is published
        if (isset($data['status']) && $data['status'] === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Ensure status is set
        if (!isset($data['status'])) {
            $data['status'] = 'draft';
        }

        $post = Post::create($data);

        // Clear cache
        $this->clearCache();

        return $post;
    }

    /**
     * Update post
     */
    public function update($id, array $data)
    {
        $post = Post::findOrFail($id);

        // Handle Image Upload
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $data['image']->store('news', 'public');
        }

        // Regenerate Slug if title changed
        if (isset($data['title']) && $data['title'] !== $post->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Update published_at if status changes to published
        if (isset($data['status']) && $data['status'] === 'published' && $post->status !== 'published') {
            $data['published_at'] = now();
        }

        $post->update($data);

        // Clear cache
        $this->clearCache();

        return $post;
    }

    /**
     * Delete post
     */
    public function delete($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $result = $post->delete();

        // Clear cache
        $this->clearCache();

        return $result;
    }

    protected function clearCache()
    {
        // Naive cache clearing. Ideally, use tags if driver supports it.
        // For file driver, we can't easily clear by tag.
        // We'll forget specific known keys if possible, but hashes make it hard.
        // Cache::flush() is safest but aggressive.
        // Given this is a small site ("cirebon-kita"), flushing application cache on content update is acceptable.
        Cache::flush();
    }
}
