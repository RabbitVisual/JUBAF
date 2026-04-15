<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\App\Http\Controllers\Admin\Concerns\UsesBlogAdminViews;
use Modules\Blog\App\Models\BlogCategory;
use Illuminate\Support\Str;

class BlogCategoriesAdminController extends Controller
{
    use UsesBlogAdminViews;

    /**
     * Display categories list
     */
    public function index()
    {
        $this->authorize('viewAny', BlogCategory::class);

        $categories = BlogCategory::withCount('posts')
            ->ordered()
            ->paginate(20);

        $estatisticas = [
            'total_categories' => BlogCategory::count(),
            'active_categories' => BlogCategory::active()->count(),
            'inactive_categories' => BlogCategory::where('is_active', false)->count(),
        ];

        return view($this->blogView('categories.index'), compact('categories', 'estatisticas'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $this->authorize('create', BlogCategory::class);

        return view($this->blogView('categories.create'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $this->authorize('create', BlogCategory::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (empty($validated['sort_order'])) {
            $validated['sort_order'] = BlogCategory::max('sort_order') + 1;
        }

        BlogCategory::create($validated);

        return redirect()->to($this->blogRoute('categories.index'))
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $this->authorize('viewAny', BlogCategory::class);

        $category = BlogCategory::with(['posts' => function ($query) {
            $query->with(['author', 'tags'])->orderBy('created_at', 'desc')->limit(10);
        }])->findOrFail($id);

        $estatisticas = [
            'total_posts' => $category->posts()->count(),
            'published_posts' => $category->publishedPosts()->count(),
            'draft_posts' => $category->posts()->where('status', 'draft')->count(),
            'total_views' => $category->posts()->sum('views_count'),
        ];

        return view($this->blogView('categories.show'), compact('category', 'estatisticas'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('update', $category);

        return view($this->blogView('categories.edit'), compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->to($this->blogRoute('categories.show', ['id' => $category->id]))
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('delete', $category);

        // Verificar se há posts nesta categoria
        if ($category->posts()->count() > 0) {
            return redirect()->to($this->blogRoute('categories.index'))
                ->with('error', 'Não é possível excluir uma categoria que possui posts.');
        }

        $category->delete();

        return redirect()->to($this->blogRoute('categories.index'))
            ->with('success', 'Categoria excluída com sucesso!');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = BlogCategory::findOrFail($id);
        $this->authorize('update', $category);
        $category->update(['is_active' => ! $category->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'Categoria ativada!' : 'Categoria desativada!'
        ]);
    }
}
