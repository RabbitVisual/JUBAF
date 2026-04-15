<?php

namespace Modules\Blog\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogComment;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogTag;

class BlogController extends Controller
{
    /**
     * Dados reutilizáveis para a coluna lateral do blog (listagens e artigo).
     *
     * @param  string|null  $constrainSearchTo  Quando definido (ex.: página de busca), as últimas publicações na sidebar
     *                                          respeitam o mesmo termo — evita misturar títulos fora dos resultados.
     * @return array{latestPostsSidebar: Collection, popularTagsSidebar: Collection, sidebarCategories: Collection}
     */
    private function publicBlogSidebar(?BlogPost $excludePost = null, ?string $constrainSearchTo = null): array
    {
        $latestPostsSidebar = BlogPost::published()
            ->with(['category', 'author'])
            ->when($excludePost, fn ($q) => $q->where('id', '!=', $excludePost->id))
            ->when($constrainSearchTo !== null && $constrainSearchTo !== '', fn ($q) => $q->search($constrainSearchTo))
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        $popularTagsSidebar = BlogTag::query()
            ->has('posts')
            ->withCount('publishedPosts')
            ->orderBy('published_posts_count', 'desc')
            ->limit(12)
            ->get();

        $sidebarCategories = BlogCategory::active()
            ->withCount('publishedPosts')
            ->ordered()
            ->get();

        return compact('latestPostsSidebar', 'popularTagsSidebar', 'sidebarCategories');
    }

    /**
     * Display blog homepage
     */
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc');

        // Filtros
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('tag')) {
            $query->byTag($request->tag);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $posts = $query->paginate(12);

        // Posts em destaque (cache alinhado a invalidação no modelo)
        $featuredPosts = Cache::remember('blog.public.featured', 300, function () {
            return BlogPost::published()
                ->featured()
                ->with(['category', 'author'])
                ->orderBy('published_at', 'desc')
                ->limit(3)
                ->get();
        });

        // Categorias para menu
        $categories = BlogCategory::active()
            ->withCount('publishedPosts')
            ->ordered()
            ->get();

        // Tags populares
        $popularTags = BlogTag::has('posts')
            ->withCount('publishedPosts')
            ->orderBy('published_posts_count', 'desc')
            ->limit(20)
            ->get();

        return view('blog::public.index', array_merge(
            compact('posts', 'featuredPosts', 'categories', 'popularTags'),
            $this->publicBlogSidebar()
        ));
    }

    /**
     * Display a specific post
     */
    public function show($slug)
    {
        $post = BlogPost::published()
            ->with(['category', 'author', 'tags', 'approvedComments.user', 'approvedComments.replies.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Incrementar visualizações
        $post->incrementViews();

        // Posts relacionados
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->with(['category', 'author'])
            ->limit(3)
            ->get();

        // Posts anteriores e próximos
        $previousPost = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        return view('blog::public.show', array_merge(
            compact('post', 'relatedPosts', 'previousPost', 'nextPost'),
            $this->publicBlogSidebar($post)
        ));
    }

    /**
     * Display posts by category
     */
    public function category($slug)
    {
        $category = BlogCategory::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = BlogPost::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog::public.category', array_merge(
            compact('category', 'posts'),
            $this->publicBlogSidebar()
        ));
    }

    /**
     * Display posts by tag
     */
    public function tag($slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::published()
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('blog_tags.id', $tag->id);
            })
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog::public.tag', array_merge(
            compact('tag', 'posts'),
            $this->publicBlogSidebar()
        ));
    }

    /**
     * Store a comment
     */
    public function storeComment(Request $request, $postId)
    {
        $post = BlogPost::published()->findOrFail($postId);

        if (! $post->allow_comments) {
            return redirect()->back()->with('error', 'Comentários não são permitidos neste post.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        $validated['post_id'] = $post->id;
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'approved'; // Aprovar automaticamente comentários de usuários logados

        // Coletar metadados do comentário
        $validated['author_ip'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();

        // Se é uma resposta, verificar se o comentário pai pertence ao mesmo post
        if (! empty($validated['parent_id'])) {
            $parentComment = BlogComment::where('id', $validated['parent_id'])
                ->where('post_id', $post->id)
                ->first();

            if (! $parentComment) {
                return redirect()->back()->with('error', 'Comentário pai inválido.');
            }
        }

        BlogComment::create($validated);

        return redirect()->back()->with('success', 'Comentário enviado! Aguarde aprovação.');
    }

    /**
     * Search posts
     */
    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        $posts = BlogPost::published()
            ->when($query !== '', fn ($q) => $q->search($query))
            ->with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $sidebarSearch = $query !== '' ? $query : null;

        return view('blog::public.search', array_merge(
            compact('posts', 'query'),
            $this->publicBlogSidebar(null, $sidebarSearch)
        ));
    }

    /**
     * RSS Feed
     */
    public function rss()
    {
        $posts = BlogPost::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        return response()->view('blog::public.rss', compact('posts'))
            ->header('Content-Type', 'application/rss+xml');
    }

    /**
     * Sitemap
     */
    public function sitemap()
    {
        $posts = BlogPost::published()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $categories = BlogCategory::active()
            ->select('slug', 'updated_at')
            ->get();

        $tags = BlogTag::has('posts')
            ->select('slug', 'updated_at')
            ->get();

        return response()->view('blog::public.sitemap', compact('posts', 'categories', 'tags'))
            ->header('Content-Type', 'application/xml');
    }
}
