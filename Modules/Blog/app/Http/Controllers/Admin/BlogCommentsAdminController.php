<?php

namespace Modules\Blog\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\App\Http\Controllers\Admin\Concerns\UsesBlogAdminViews;
use Modules\Blog\App\Models\BlogComment;
use Modules\Blog\App\Models\BlogPost;

class BlogCommentsAdminController extends Controller
{
    use UsesBlogAdminViews;

    public function index(Request $request)
    {
        $this->authorize('viewAny', BlogComment::class);

        $filters = $request->only(['search', 'status', 'post']);

        $query = BlogComment::with(['post', 'user', 'parent'])
            ->orderBy('created_at', 'desc');

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('content', 'like', "%{$filters['search']}%")
                    ->orWhere('author_name', 'like', "%{$filters['search']}%")
                    ->orWhere('author_email', 'like', "%{$filters['search']}%");
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['post'])) {
            $query->where('post_id', $filters['post']);
        }

        $comments = $query->paginate(20);

        $estatisticas = [
            'total_comments' => BlogComment::count(),
            'approved_comments' => BlogComment::approved()->count(),
            'pending_comments' => BlogComment::pending()->count(),
            'rejected_comments' => BlogComment::where('status', 'rejected')->count(),
        ];

        $posts = BlogPost::select('id', 'title')->orderBy('title')->get();

        return view($this->blogView('comments.index'), compact('comments', 'filters', 'estatisticas', 'posts'));
    }

    public function show($id)
    {
        $comment = BlogComment::with(['post', 'user', 'parent', 'replies.user'])
            ->findOrFail($id);

        $this->authorize('view', $comment);

        return view($this->blogView('comments.show'), compact('comment'));
    }

    public function approve($id)
    {
        $comment = BlogComment::findOrFail($id);
        $this->authorize('update', $comment);

        $comment->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Comentário aprovado com sucesso!');
    }

    public function reject($id)
    {
        $comment = BlogComment::findOrFail($id);
        $this->authorize('update', $comment);

        $comment->update([
            'status' => 'rejected',
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Comentário rejeitado com sucesso!');
    }

    public function destroy($id)
    {
        $comment = BlogComment::findOrFail($id);
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->to($this->blogRoute('comments.index'))
            ->with('success', 'Comentário excluído com sucesso!');
    }

    public function bulkApprove(Request $request)
    {
        $this->authorize('viewAny', BlogComment::class);

        $request->validate([
            'comments' => 'required|array',
            'comments.*' => 'exists:blog_comments,id',
        ]);

        BlogComment::whereIn('id', $request->comments)
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

        return redirect()->back()
            ->with('success', 'Comentários aprovados com sucesso!');
    }

    public function bulkReject(Request $request)
    {
        $this->authorize('viewAny', BlogComment::class);

        $request->validate([
            'comments' => 'required|array',
            'comments.*' => 'exists:blog_comments,id',
        ]);

        BlogComment::whereIn('id', $request->comments)
            ->update([
                'status' => 'rejected',
                'approved_at' => null,
                'approved_by' => null,
            ]);

        return redirect()->back()
            ->with('success', 'Comentários rejeitados com sucesso!');
    }

    public function bulkDelete(Request $request)
    {
        $this->authorize('viewAny', BlogComment::class);

        $request->validate([
            'comments' => 'required|array',
            'comments.*' => 'exists:blog_comments,id',
        ]);

        BlogComment::whereIn('id', $request->comments)->delete();

        return redirect()->back()
            ->with('success', 'Comentários excluídos com sucesso!');
    }
}
