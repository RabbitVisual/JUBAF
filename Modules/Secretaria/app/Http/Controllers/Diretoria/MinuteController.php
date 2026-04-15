<?php

namespace Modules\Secretaria\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Igrejas\App\Models\Church;
use Modules\Secretaria\App\Http\Controllers\Concerns\RendersSecretariaPanelViews;
use Modules\Secretaria\App\Models\Meeting;
use Modules\Secretaria\App\Models\Minute;
use Modules\Secretaria\App\Models\MinuteAttachment;
use Modules\Secretaria\App\Models\MinuteTemplate;
use Modules\Secretaria\App\Services\AtaWorkflowService;
use Modules\Secretaria\App\Services\PdfGenerationService;
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
        return 'layouts.app';
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

        $minute = new Minute(['content' => $templateBody]);

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
            'content' => ['nullable', 'string'],
            'executive_summary' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['status'] = 'draft';
        $data['created_by_id'] = $request->user()->id;
        $data['meeting_date'] = $data['meeting_id']
            ? Meeting::query()->whereKey((int) $data['meeting_id'])->value(DB::raw('DATE(starts_at)'))
            : null;
        $data['uuid'] = (string) \Illuminate\Support\Str::uuid();
        $minute = Minute::create($data);

        return redirect()->route($this->routePrefix().'.edit', $minute)->with('success', 'Ata em rascunho.');
    }

    public function show(Minute $minute)
    {
        $this->authorize('view', $minute);
        $minute->load(['meeting', 'church', 'creator', 'approvedBy', 'attachments']);
        $minute->load(['signatures.user']);

        return $this->secretariaView('minutes.show', [
            'minute' => $minute,
            'requiredSignerRoles' => (array) config('secretaria.required_minute_signers', ['presidente', 'secretario-1']),
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
            'content' => ['nullable', 'string'],
            'executive_summary' => ['nullable', 'string', 'max:2000'],
            'attachments' => ['nullable', 'array', 'max:15'],
            'attachments.*' => ['file', 'max:15360', 'mimes:pdf,doc,docx,jpg,jpeg,png,txt'],
            'attachment_kind' => ['nullable', 'string', 'in:attachment,ata_anterior,oficio'],
        ]);
        $minute->update([
            'meeting_id' => $data['meeting_id'] ?? null,
            'church_id' => $data['church_id'] ?? null,
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'executive_summary' => $data['executive_summary'] ?? null,
            'meeting_date' => ! empty($data['meeting_id'])
                ? Meeting::query()->whereKey((int) $data['meeting_id'])->value(DB::raw('DATE(starts_at)'))
                : null,
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

    public function submit(Minute $minute, AtaWorkflowService $workflowService)
    {
        $this->authorize('requestSignatures', $minute);
        $workflowService->requestSignatures($minute);

        return redirect()->back()->with('success', 'Ata enviada para recolha de assinaturas.');
    }

    public function sign(Request $request, Minute $minute, AtaWorkflowService $workflowService)
    {
        $this->authorize('sign', $minute);
        $data = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        try {
            $signedMinute = $workflowService->sign($minute, $request->user(), $data['password'], $request);
        } catch (AuthorizationException $e) {
            return redirect()->back()->withErrors(['password' => $e->getMessage()]);
        }

        $message = $signedMinute->status === 'published'
            ? 'Assinatura registada e ata publicada com sucesso.'
            : 'Assinatura registada com sucesso.';

        return redirect()->back()->with('success', $message);
    }

    public function archive(Request $request, Minute $minute)
    {
        $this->authorize('archive', $minute);
        $minute->update([
            'status' => 'archived',
        ]);

        return redirect()->back()->with('success', 'Ata arquivada (somente leitura).');
    }

    public function pdf(Minute $minute, PdfGenerationService $pdfGenerationService)
    {
        $this->authorize('downloadPdf', $minute);

        if (! $minute->pdf_path || ! Storage::disk('local')->exists($minute->pdf_path)) {
            $pdfGenerationService->generateAndStore($minute);
            $minute->refresh();
        }

        return Storage::disk('local')->download(
            $minute->pdf_path,
            'ata-'.$minute->id.'.pdf'
        );
    }
}
