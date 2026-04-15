<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DevotionalRequest;
use App\Models\BoardMember;
use App\Models\Devotional;
use App\Models\SystemConfig;
use App\Models\User;
use App\Services\DevotionalScriptureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Bible\App\Services\BibleApiService;
use Modules\Notificacoes\App\Models\Notificacao;

class DevotionalController extends Controller
{
    protected function routePrefix(): string
    {
        return 'admin.devotionals';
    }

    protected function viewPrefix(): string
    {
        return 'admin::devotionals';
    }

    protected function panelLayout(): string
    {
        return 'admin::layouts.admin';
    }

    public function index(): View
    {
        $this->authorize('viewAny', Devotional::class);

        $rows = Devotional::query()
            ->with(['user', 'boardMember'])
            ->orderByDesc('devotional_date')
            ->orderByDesc('updated_at')
            ->paginate(20);

        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.index', compact('rows', 'routePrefix', 'layout'));
    }

    public function create(): View
    {
        $this->authorize('create', Devotional::class);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $boardMembers = BoardMember::query()->where('is_active', true)->orderBy('sort_order')->orderBy('full_name')->get();
        $devotional = new Devotional([
            'status' => Devotional::STATUS_DRAFT,
            'author_type' => Devotional::AUTHOR_USER,
            'user_id' => auth()->id(),
        ]);

        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.create', compact('users', 'boardMembers', 'devotional', 'routePrefix', 'layout'));
    }

    public function store(DevotionalRequest $request): RedirectResponse
    {
        $this->authorize('create', Devotional::class);
        if ($request->input('status') === Devotional::STATUS_PUBLISHED) {
            $this->authorize('publish', Devotional::class);
        }

        $data = $this->payloadFromRequest($request, null);
        $devotional = Devotional::create($data);
        if ($data['status'] === Devotional::STATUS_PUBLISHED) {
            $this->notifyDevotionalPublishedIfEnabled($devotional);
        }

        return redirect()->route($this->routePrefix().'.index')
            ->with('success', 'Devocional criado.');
    }

    public function edit(Devotional $devotional): View
    {
        $this->authorize('update', $devotional);

        $users = User::query()->orderBy('name')->get(['id', 'name', 'email']);
        $boardMembers = BoardMember::query()->where('is_active', true)->orderBy('sort_order')->orderBy('full_name')->get();

        $routePrefix = $this->routePrefix();
        $layout = $this->panelLayout();

        return view($this->viewPrefix().'.edit', compact('devotional', 'users', 'boardMembers', 'routePrefix', 'layout'));
    }

    public function update(DevotionalRequest $request, Devotional $devotional): RedirectResponse
    {
        $this->authorize('update', $devotional);
        if ($request->input('status') === Devotional::STATUS_PUBLISHED) {
            $this->authorize('publish', Devotional::class);
        }

        $wasPublished = $devotional->status === Devotional::STATUS_PUBLISHED;
        $data = $this->payloadFromRequest($request, $devotional);
        $devotional->update($data);

        if ($data['status'] === Devotional::STATUS_PUBLISHED && ! $wasPublished) {
            $devotional->refresh();
            $this->notifyDevotionalPublishedIfEnabled($devotional);
        }

        return redirect()->route($this->routePrefix().'.index')
            ->with('success', 'Devocional atualizado.');
    }

    public function destroy(Devotional $devotional): RedirectResponse
    {
        $this->authorize('delete', $devotional);

        if ($devotional->cover_image_path) {
            Storage::disk('public')->delete($devotional->cover_image_path);
        }
        if ($devotional->video_path) {
            Storage::disk('public')->delete($devotional->video_path);
        }
        $devotional->delete();

        return redirect()->route($this->routePrefix().'.index')
            ->with('success', 'Devocional removido.');
    }

    public function fetchScripture(Request $request, DevotionalScriptureService $service): JsonResponse
    {
        $this->authorize('viewAny', Devotional::class);

        $validated = $request->validate([
            'ref' => ['required', 'string', 'max:200'],
            'version_id' => ['nullable', 'integer', 'exists:bible_versions,id'],
        ]);

        if (! module_enabled('Bible')) {
            return response()->json([
                'ok' => false,
                'message' => 'O módulo Bíblia está desativado.',
            ], 422);
        }

        $result = $service->resolve($validated['ref'], $validated['version_id'] ?? null);
        if ($result === null) {
            return response()->json([
                'ok' => false,
                'message' => 'Não foi possível resolver a referência. Use o formato «Salmos 3:1-5» (espaço antes do capítulo) e um intervalo até 40 versículos.',
            ], 422);
        }

        return response()->json(['ok' => true, 'data' => $result]);
    }

    /**
     * Lista de livros para o assistente de passagem (integração com o módulo Bíblia).
     */
    public function bibleBooks(Request $request, BibleApiService $bibleApi): JsonResponse
    {
        $this->authorize('viewAny', Devotional::class);

        $validated = $request->validate([
            'version_id' => ['nullable', 'integer', 'exists:bible_versions,id'],
        ]);

        if (! module_enabled('Bible')) {
            return response()->json([
                'ok' => false,
                'message' => 'O módulo Bíblia está desativado.',
            ], 422);
        }

        $books = $bibleApi->getBooks($validated['version_id'] ?? null);

        return response()->json([
            'ok' => true,
            'data' => $books->map(fn ($b) => [
                'id' => $b->id,
                'name' => $b->name,
                'abbreviation' => $b->abbreviation,
                'book_number' => $b->book_number,
                'testament' => $b->testament,
            ])->values(),
        ]);
    }

    /**
     * Capítulos de um livro (para o assistente de passagem).
     */
    public function bibleChapters(Request $request, BibleApiService $bibleApi): JsonResponse
    {
        $this->authorize('viewAny', Devotional::class);

        $validated = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
        ]);

        if (! module_enabled('Bible')) {
            return response()->json([
                'ok' => false,
                'message' => 'O módulo Bíblia está desativado.',
            ], 422);
        }

        $chapters = $bibleApi->getChapters($validated['book_id']);

        return response()->json([
            'ok' => true,
            'data' => $chapters->map(fn ($c) => [
                'id' => $c->id,
                'chapter_number' => $c->chapter_number,
                'total_verses' => $c->total_verses,
            ])->values(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function payloadFromRequest(DevotionalRequest $request, ?Devotional $existing): array
    {
        $data = $request->validated();

        $slugIn = trim((string) ($data['slug'] ?? ''));
        if ($slugIn === '' && $existing) {
            $data['slug'] = $existing->slug;
        } elseif ($slugIn === '') {
            $data['slug'] = Devotional::slugFromTitle($data['title']);
        } else {
            $data['slug'] = Str::slug($slugIn) ?: Devotional::slugFromTitle($data['title']);
        }

        $this->applyAuthorConstraints($request, $data);

        if ($data['status'] === Devotional::STATUS_PUBLISHED) {
            $data['published_at'] = $existing?->published_at ?? now();
        } else {
            $data['published_at'] = null;
        }

        if ($request->hasFile('cover')) {
            if ($existing?->cover_image_path) {
                Storage::disk('public')->delete($existing->cover_image_path);
            }
            $data['cover_image_path'] = $request->file('cover')->store('devotionals/covers', 'public');
        } elseif ($existing) {
            unset($data['cover_image_path']);
        }

        if ($request->boolean('clear_devotional_video')) {
            if ($existing?->video_path) {
                Storage::disk('public')->delete($existing->video_path);
            }
            $data['video_path'] = null;
            $data['video_url'] = null;
        } elseif ($request->hasFile('video')) {
            if ($existing?->video_path) {
                Storage::disk('public')->delete($existing->video_path);
            }
            $data['video_path'] = $request->file('video')->store('devotionals/videos', 'public');
            $data['video_url'] = null;
        } else {
            unset($data['video_path']);
            $url = trim((string) $request->input('video_url', ''));
            if ($url !== '') {
                if ($existing?->video_path) {
                    Storage::disk('public')->delete($existing->video_path);
                }
                $data['video_path'] = null;
                $data['video_url'] = $url;
            } elseif ($existing) {
                unset($data['video_url']);
            } else {
                $data['video_url'] = null;
            }
        }

        return $data;
    }

    protected function notifyDevotionalPublishedIfEnabled(Devotional $devotional): void
    {
        if (! module_enabled('Notificacoes')) {
            return;
        }
        if (! (bool) SystemConfig::get('integrations_notify_on_devotional_published', false)) {
            return;
        }

        $actionUrl = route('devocionais.show', $devotional->slug);
        $users = User::permission('notificacoes.view')->get(['id']);
        foreach ($users as $user) {
            Notificacao::createNotification(
                type: 'info',
                title: 'Novo devocional publicado',
                message: $devotional->title,
                userId: (int) $user->id,
                role: null,
                data: null,
                actionUrl: $actionUrl,
                moduleSource: 'Homepage',
                entityType: 'devotional',
                entityId: (int) $devotional->id,
                panel: 'admin'
            );
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function applyAuthorConstraints(Request $request, array &$data): void
    {
        $data['user_id'] = null;
        $data['board_member_id'] = null;
        $data['guest_author_name'] = null;
        $data['guest_author_title'] = null;

        if ($data['author_type'] === Devotional::AUTHOR_USER) {
            $data['user_id'] = $request->integer('user_id') ?: (int) auth()->id();
        } elseif ($data['author_type'] === Devotional::AUTHOR_BOARD_MEMBER) {
            $data['board_member_id'] = $request->integer('board_member_id') ?: null;
        } elseif ($data['author_type'] === Devotional::AUTHOR_PASTOR_GUEST) {
            $data['guest_author_name'] = $request->input('guest_author_name');
            $data['guest_author_title'] = $request->input('guest_author_title');
        }
    }
}
