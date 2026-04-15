<?php

namespace Modules\Blog\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\App\Models\BlogPost;

class BlogPainelController extends Controller
{
    /**
     * Lista posts publicados no painel Jovens ou Líderes.
     */
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->with(['category', 'author'])
            ->orderByDesc('published_at');

        if ($request->filled('q')) {
            $query->search($request->string('q')->toString());
        }

        $posts = $query->paginate(12)->withQueryString();

        $view = match (true) {
            $request->routeIs('jovens.*') => 'blog::paineljovens.index',
            $request->routeIs('lideres.*') => 'blog::painellider.index',
            default => abort(404),
        };

        return view($view, compact('posts'));
    }

    /**
     * Detalhe de um post (leitura) no painel.
     */
    public function show(Request $request, string $slug)
    {
        $post = BlogPost::published()
            ->with(['category', 'author', 'tags', 'approvedComments.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $post->incrementViews();

        $view = match (true) {
            $request->routeIs('jovens.*') => 'blog::paineljovens.show',
            $request->routeIs('lideres.*') => 'blog::painellider.show',
            default => abort(404),
        };

        return view($view, compact('post'));
    }
}
