<?php

namespace Modules\Secretaria\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Igrejas\App\Models\Church;
use Modules\Secretaria\App\Http\Controllers\Concerns\RendersSecretariaPanelViews;
use Modules\Secretaria\App\Models\Meeting;
use Modules\Secretaria\App\Events\MinutePublished;
use Modules\Secretaria\App\Models\Minute;
use Modules\Secretaria\App\Models\MinuteAttachment;
use Modules\Secretaria\App\Models\MinuteTemplate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MinuteController extends Controller
{
    use RendersSecretariaPanelViews;

    /**
     * @return \Illuminate\Support\Collection<int, \Modules\Igrejas\App\Models\Church>
     */
    protected function churchesForMinuteForm(Request $request): \Illuminate\Support\Collection
    {
        $q = Church::query()->where('is_active', true)->orderBy('name');
        if ($request->user()) {
            ErpChurchScope::applyToChurchQuery($q, $request->user());
        }

        return $q->get();
    }

    protected function routePrefix(): string
    {
        return 'diretoria.secretaria.atas';
    }

    protected function panelLayout(): string
    {
        return 'paineldiretoria::components.layouts.app';
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Minute::class);
        $q = Minute::query()->with(['meeting', 'church', 'creator'])->orderByDesc('updated_at');
        if ($request->user()) {
            ErpChurchScope::applyToSecretariaMinuteQuery($q, $request->user());
        }
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        $minutes = $q->paginate(20)->withQueryString();

        return $this->secretariaView('minutes.index', [
            'minutes' => $minutes,
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Minute::class);

        $templateBody = null;
        if ($request->filled('template')) {
            $tpl = MinuteTemplate::query()->active()->where('slug', $request->string('template'))->first();
            $templateBody = $tpl?->body;
        }

        $minute = new Minute(['body' => $templateBody]);

        return $this->secretariaView('minutes.create', [
            'minute' => $minute,
            'churches' => $this->churchesForMinuteForm($request),
            'meetings' => Meeting::query()->orderByDesc('starts_at')->limit(60)->get(),
            'templates' => MinuteTemplate::query()->active()->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Minute::class);
        $data = $request->validate([
            'meeting_id' => ['nullable', 'exists:secretaria_meetings,id'],
            'church_id' => ['nullable', 'exists:igrejas_churches,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'executive_summary' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['status'] = 'draft';
        $data['created_by_id'] = $request->user()->id;
        $minute = Minute::create($data);

        return redirect()->route($this->routePrefix().'.edit', $minute)->with('success', 'Ata em rascunho.');
    }

    public function show(Minute $minute)
    {
        $this->authorize('view', $minute);
        $minute->load(['meeting', 'church', 'creator', 'approvedBy', 'attachments']);

        return $this->secretariaView('minutes.show', [
            'minute' => $minute,
        ]);
    }

    public function edit(Request $request, Minute $minute)
    {
        $this->authorize('update', $minute);
        $minute->load('attachments');

        return $this->secretariaView('minutes.edit', [
            'minute' => $minute,
            'churches' => $this->churchesForMinuteForm($request),
            'meetings' => Meeting::query()->orderByDesc('starts_at')->limit(60)->get(),
        ]);
    }

    public function update(Request $request, Minute $minute)
    {
        $this->authorize('update', $minute);
        $data = $request->validate([
            'meeting_id' => ['nullable', 'exists:secretaria_meetings,id'],
            'church_id' => ['nullable', 'exists:igrejas_churches,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string'],
            'executive_summary' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array', 'max:15'],
            'attachments.*' => ['file', 'max:15360', 'mimes:pdf,doc,docx,jpg,jpeg,png,txt'],
            'attachment_kind' => ['nullable', 'string', 'in:attachment,ata_anterior,oficio'],
        ]);
        $minute->update([
            'meeting_id' => $data['meeting_id'] ?? null,
            'church_id' => $data['church_id'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'] ?? null,
            'executive_summary' => $data['executive_summary'] ?? null,
        ]);

        $kind = $data['attachment_kind'] ?? MinuteAttachment::KIND_ATTACHMENT;
        $sortBase = (int) $minute->attachments()->max('sort_order');
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $sortBase++;
                $path = $file->store('secretaria/minute-attachments/'.$minute->id, 'local');
                MinuteAttachment::create([
                    'minute_id' => $minute->id,
                    'related_minute_id' => null,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'kind' => $kind,
                    'sort_order' => $sortBase,
                ]);
            }
        }

        return redirect()->route($this->routePrefix().'.show', $minute)->with('success', 'Ata guardada.');
    }

    public function attachmentDownload(Minute $minute, MinuteAttachment $minute_attachment): StreamedResponse
    {
        abort_unless((int) $minute_attachment->minute_id === (int) $minute->id, 404);
        $this->authorize('view', $minute);
        abort_unless(Storage::disk('local')->exists($minute_attachment->path), 404);

        return Storage::disk('local')->download(
            $minute_attachment->path,
            $minute_attachment->original_name ?? 'anexo'
        );
    }

    public function attachmentDestroy(Minute $minute, MinuteAttachment $minute_attachment)
    {
        abort_unless((int) $minute_attachment->minute_id === (int) $minute->id, 404);
        $this->authorize('update', $minute);
        Storage::disk('local')->delete($minute_attachment->path);
        $minute_attachment->delete();

        return redirect()->back()->with('success', 'Anexo removido.');
    }

    public function destroy(Minute $minute)
    {
        $this->authorize('delete', $minute);
        $minute->delete();

        return redirect()->route($this->routePrefix().'.index')->with('success', 'Ata eliminada.');
    }

    public function submit(Minute $minute)
    {
        $this->authorize('submit', $minute);
        $minute->update([
            'status' => 'pending_approval',
            'submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ata enviada para aprovação do executivo.');
    }

    public function approve(Request $request, Minute $minute)
    {
        $this->authorize('approve', $minute);
        $minute->update([
            'status' => 'approved',
            'approved_by_id' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Ata aprovada. Pode publicar quando desejar.');
    }

    public function publish(Request $request, Minute $minute)
    {
        $this->authorize('publish', $minute);
        DB::transaction(function () use ($minute) {
            $publishedAt = now();
            $protocol = 'ATA-'.$publishedAt->year.'-'.str_pad((string) $minute->id, 5, '0', STR_PAD_LEFT);
            $minute->update([
                'status' => 'published',
                'published_at' => $publishedAt,
                'locked_at' => $publishedAt,
                'protocol_number' => $protocol,
            ]);
            $minute->refresh();
            $minute->update([
                'content_checksum' => $minute->computeContentChecksum(),
            ]);
        });

        $fresh = $minute->fresh();
        event(new MinutePublished($fresh, $request->user()));

        return redirect()->back()->with('success', 'Ata publicada e bloqueada para edição.');
    }

    public function archive(Request $request, Minute $minute)
    {
        $this->authorize('archive', $minute);
        $minute->update([
            'status' => 'archived',
        ]);

        return redirect()->back()->with('success', 'Ata arquivada (somente leitura).');
    }

    public function pdf(Minute $minute)
    {
        $this->authorize('downloadPdf', $minute);
        $minute->load(['meeting', 'church', 'creator', 'approvedBy', 'attachments']);
        $pdf = Pdf::loadView('secretaria::components.minute-pdf', ['minute' => $minute]);

        return $pdf->download('ata-'.$minute->id.'.pdf');
    }
}
