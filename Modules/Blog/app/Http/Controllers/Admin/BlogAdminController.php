<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Blog\App\Http\Controllers\Admin\Concerns\UsesBlogAdminViews;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogComment;
use Modules\Blog\App\Models\BlogPost;
use Modules\Blog\App\Models\BlogTag;

class BlogAdminController extends Controller
{
    use UsesBlogAdminViews;

    public function index(Request $request)
    {
        $this->authorize('viewAny', BlogPost::class);

        $filters = $request->only(['search', 'status', 'category', 'author']);

        $query = BlogPost::with(['category', 'author', 'tags'])
            ->orderBy('created_at', 'desc');

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (! empty($filters['author'])) {
            $query->where('author_id', $filters['author']);
        }

        $posts = $query->paginate(20);

        $estatisticas = $this->calcularEstatisticas();

        $categories = BlogCategory::active()->ordered()->get();
        $authors = \App\Models\User::whereHas('blogPosts')->orderBy('name')->get();

        return view($this->blogView('index'), compact('posts', 'filters', 'estatisticas', 'categories', 'authors'));
    }

    public function create()
    {
        $this->authorize('create', BlogPost::class);

        $categories = BlogCategory::active()->ordered()->get();
        $tags = BlogTag::orderBy('name')->get();

        return view($this->blogView('create'), compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', BlogPost::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:blog_tags,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'integer',
            'attachments.*' => 'nullable|file|mimes:pdf|max:10240',
            'og_image' => 'nullable|image|max:2048',
            'og_description' => 'nullable|string|max:500',
            'gallery_before_after' => 'nullable|array',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $tagIds = array_values(array_unique(array_map('intval', $validated['tag_ids'] ?? [])));
        unset($validated['tag_ids']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $this->uploadImage($request->file('og_image'));
        }

        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $this->uploadImage($image);
            }
            $validated['gallery_images'] = $galleryImages;
        }

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $this->uploadAttachment($file),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        if (! empty($validated['meta_keywords'])) {
            $validated['meta_keywords'] = array_map('trim', explode(',', $validated['meta_keywords']));
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        $post->tags()->sync($tagIds);

        return redirect()->to($this->blogRoute('show', ['blog' => $post->id]))
            ->with('success', 'Post criado com sucesso!');
    }

    public function show($id)
    {
        $this->authorize('viewAny', BlogPost::class);

        $post = BlogPost::with([
            'category',
            'author',
            'tags',
            'comments.user',
            'comments.parent',
            'views',
        ])->findOrFail($id);

        $estatisticas = [
            'total_views' => $post->views_count,
            'total_likes' => $post->likes_count,
            'total_shares' => $post->shares_count,
            'total_comments' => $post->comments()->count(),
            'approved_comments' => $post->approvedComments()->count(),
            'pending_comments' => $post->comments()->where('status', 'pending')->count(),
        ];

        $comentariosRecentes = $post->comments()
            ->with(['user', 'parent'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view($this->blogView('show'), compact('post', 'estatisticas', 'comentariosRecentes'));
    }

    public function edit($id)
    {
        $post = BlogPost::with(['tags'])->findOrFail($id);
        $this->authorize('update', $post);

        $categories = BlogCategory::active()->ordered()->get();
        $tags = BlogTag::orderBy('name')->get();

        return view($this->blogView('edit'), compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,'.$id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:blog_tags,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'integer',
            'attachments.*' => 'nullable|file|mimes:pdf|max:10240',
            'og_image' => 'nullable|image|max:2048',
            'og_description' => 'nullable|string|max:500',
            'gallery_before_after' => 'nullable|array',
        ]);

        $tagIds = array_values(array_unique(array_map('intval', $validated['tag_ids'] ?? [])));
        unset($validated['tag_ids']);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        if ($request->hasFile('og_image')) {
            if ($post->og_image) {
                Storage::disk('public')->delete($post->og_image);
            }
            $validated['og_image'] = $this->uploadImage($request->file('og_image'));
        }

        if ($request->hasFile('gallery_images')) {
            if ($post->gallery_images) {
                foreach ($post->gallery_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $this->uploadImage($image);
            }
            $validated['gallery_images'] = $galleryImages;
        }

        if ($request->hasFile('attachments')) {
            if ($post->attachments) {
                foreach ($post->attachments as $att) {
                    Storage::disk('public')->delete($att['path']);
                }
            }

            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $this->uploadAttachment($file),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        if (! empty($validated['meta_keywords'])) {
            $validated['meta_keywords'] = array_map('trim', explode(',', $validated['meta_keywords']));
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        $post->tags()->sync($tagIds);

        return redirect()->to($this->blogRoute('show', ['blog' => $post->id]))
            ->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize('delete', $post);

        try {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            if ($post->gallery_images) {
                foreach ($post->gallery_images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            if ($post->attachments) {
                foreach ($post->attachments as $att) {
                    Storage::disk('public')->delete($att['path']);
                }
            }

            $post->delete();

            return redirect()->to($this->blogRoute('index'))
                ->with('success', 'Post excluído com sucesso!');
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Erro ao excluir post: '.$e->getMessage());
        }
    }

    private function uploadImage($file): string
    {
        $filename = Str::random(40).'.'.$file->getClientOriginalExtension();
        $path = 'blog/images/'.date('Y/m');

        Storage::disk('public')->makeDirectory($path);

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($file);
        $image->scaleDown(width: 1200);

        $fullPath = $path.'/'.$filename;
        Storage::disk('public')->put($fullPath, (string) $image->encode());

        return $fullPath;
    }

    private function uploadAttachment($file): string
    {
        $filename = Str::random(40).'.'.$file->getClientOriginalExtension();
        $path = 'blog/attachments/'.date('Y/m');
        Storage::disk('public')->putFileAs($path, $file, $filename);

        return $path.'/'.$filename;
    }

    private function calcularEstatisticas(): array
    {
        try {
            return [
                'total_posts' => BlogPost::count(),
                'published_posts' => BlogPost::published()->count(),
                'draft_posts' => BlogPost::where('status', 'draft')->count(),
                'featured_posts' => BlogPost::featured()->count(),
                'total_categories' => BlogCategory::count(),
                'total_tags' => BlogTag::count(),
                'total_comments' => BlogComment::count(),
                'pending_comments' => BlogComment::pending()->count(),
                'total_views' => BlogPost::sum('views_count'),
            ];
        } catch (\Throwable $e) {
            report($e);

            return [
                'total_posts' => 0,
                'published_posts' => 0,
                'draft_posts' => 0,
                'featured_posts' => 0,
                'total_categories' => 0,
                'total_tags' => 0,
                'total_comments' => 0,
                'pending_comments' => 0,
                'total_views' => 0,
            ];
        }
    }

    public function uploadEditorImage(Request $request)
    {
        $this->authorize('create', BlogPost::class);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'nullable|string',
        ]);

        try {
            $file = $request->file('image');
            $filename = time().'_'.uniqid('', true).'.'.$file->getClientOriginalExtension();

            $path = public_path('storage/blog/images');
            if (! file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $file->move($path, $filename);

            return response()->json([
                'success' => true,
                'url' => asset('storage/blog/images/'.$filename),
                'filename' => $filename,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload da imagem: '.$e->getMessage(),
            ], 500);
        }
    }

    public function generateBulletin(Request $request)
    {
        $this->authorize('viewAny', BlogPost::class);

        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $posts = BlogPost::whereMonth('published_at', $month)
            ->whereYear('published_at', $year)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        return view($this->blogView('bulletin'), compact('posts', 'month', 'year'));
    }

    public function redactImage(Request $request)
    {
        $this->authorize('create', BlogPost::class);

        $request->validate([
            'image_path' => 'required|string',
            'image_data' => 'required|string',
        ]);

        $path = $request->input('image_path');
        $relativePath = str_replace('/storage/', '', $path);

        if (! Str::contains($relativePath, 'blog/images')) {
            return response()->json(['success' => false, 'message' => 'Caminho de imagem inválido.'], 403);
        }

        try {
            $imageData = $request->input('image_data');
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);

            Storage::disk('public')->put($relativePath, base64_decode($image));

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['success' => false, 'message' => 'Erro ao salvar imagem: '.$e->getMessage()], 500);
        }
    }
}
