<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\App\Http\Controllers\Admin\Concerns\UsesBlogAdminViews;
use Modules\Blog\App\Models\BlogTag;
use Illuminate\Support\Str;

class BlogTagsAdminController extends Controller
{
    use UsesBlogAdminViews;

    public function index()
    {
        $this->authorize('viewAny', BlogTag::class);

        $tags = BlogTag::withCount('posts')
            ->orderBy('name')
            ->paginate(20);

        $estatisticas = [
            'total_tags' => BlogTag::count(),
            'used_tags' => BlogTag::has('posts')->count(),
            'unused_tags' => BlogTag::doesntHave('posts')->count(),
        ];

        return view($this->blogView('tags.index'), compact('tags', 'estatisticas'));
    }

    public function create()
    {
        $this->authorize('create', BlogTag::class);

        return view($this->blogView('tags.create'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', BlogTag::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_tags,slug',
            'color' => 'nullable|string|max:7',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        BlogTag::create($validated);

        return redirect()->to($this->blogRoute('tags.index'))
            ->with('success', 'Tag criada com sucesso!');
    }

    public function show($id)
    {
        $this->authorize('viewAny', BlogTag::class);

        $tag = BlogTag::with(['posts' => function ($query) {
            $query->with(['author', 'category'])->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);

        $estatisticas = [
            'total_posts' => $tag->posts()->count(),
            'published_posts' => $tag->publishedPosts()->count(),
            'draft_posts' => $tag->posts()->where('status', 'draft')->count(),
            'total_views' => $tag->posts()->sum('views_count'),
        ];

        return view($this->blogView('tags.show'), compact('tag', 'estatisticas'));
    }

    public function edit($id)
    {
        $tag = BlogTag::findOrFail($id);
        $this->authorize('update', $tag);

        return view($this->blogView('tags.edit'), compact('tag'));
    }

    public function update(Request $request, $id)
    {
        $tag = BlogTag::findOrFail($id);
        $this->authorize('update', $tag);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_tags,slug,'.$id,
            'color' => 'nullable|string|max:7',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag->update($validated);

        return redirect()->to($this->blogRoute('tags.show', ['id' => $tag->id]))
            ->with('success', 'Tag atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $tag = BlogTag::findOrFail($id);
        $this->authorize('delete', $tag);
        $tag->delete();

        return redirect()->to($this->blogRoute('tags.index'))
            ->with('success', 'Tag excluída com sucesso!');
    }

    public function cleanUnused()
    {
        $this->authorize('viewAny', BlogTag::class);

        $deletedCount = BlogTag::doesntHave('posts')->delete();

        return redirect()->to($this->blogRoute('tags.index'))
            ->with('success', "Foram removidas {$deletedCount} tags não utilizadas.");
    }
}
