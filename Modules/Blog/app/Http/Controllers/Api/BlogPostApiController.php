<?php

namespace Modules\Blog\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogTag;

class BlogPostApiController extends Controller
{
    /**
     * Lista posts publicados (API pública).
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::published()
            ->with(['category', 'author'])
            ->orderByDesc('published_at');

        if ($request->filled('search')) {
            $query->search($request->string('search')->toString());
        }

        if ($request->filled('category')) {
            $query->byCategory($request->string('category')->toString());
        }

        $perPage = min((int) $request->input('per_page', 15), 50);
        $posts = $query->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * Detalhe de um post publicado por slug.
     */
    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        return response()->json($post);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', BlogPost::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['author_id'] = $request->user()->id;

        if (($validated['status'] ?? '') === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        $post = BlogPost::create($validated);

        if (is_array($tags)) {
            $this->syncTags($post, $tags);
        }

        $post->load(['category', 'author', 'tags']);

        return response()->json($post, 201);
    }

    public function update(Request $request, BlogPost $post): JsonResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,'.$post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:blog_categories,id',
            'status' => 'sometimes|required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ]);

        $tags = $validated['tags'] ?? null;
        unset($validated['tags']);

        if (isset($validated['slug']) && $validated['slug'] === '') {
            $validated['slug'] = Str::slug($validated['title'] ?? $post->title);
        }

        $post->update($validated);

        if (is_array($tags)) {
            $this->syncTags($post, $tags);
        }

        $post->load(['category', 'author', 'tags']);

        return response()->json($post);
    }

    public function destroy(BlogPost $post): JsonResponse
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * @param  array<int, string>  $tagNames
     */
    private function syncTags(BlogPost $post, array $tagNames): void
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if ($tagName === '') {
                continue;
            }
            $tag = BlogTag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);
    }
}
