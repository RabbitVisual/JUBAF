<?php

namespace Modules\Secretaria\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Secretaria\App\Http\Controllers\Concerns\RendersSecretariaPanelViews;
use Illuminate\Http\Request;
use Modules\Secretaria\App\Models\Convocation;
use Modules\Secretaria\App\Services\SecretariaIntegrationBus;
use Modules\Secretaria\App\Services\SecretariaNotificationDispatcher;

class ConvocationController extends Controller
{
    use RendersSecretariaPanelViews;

    protected function routePrefix(): string
    {
        return 'diretoria.secretaria.convocatorias';
    }

    protected function panelLayout(): string
    {
        return 'paineldiretoria::components.layouts.app';
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Convocation::class);
        $q = Convocation::query()->with(['meeting', 'creator'])->orderByDesc('assembly_at');
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }
        $convocations = $q->paginate(20)->withQueryString();

        return $this->secretariaView('convocations.index', [
            'convocations' => $convocations,
            'filters' => $request->only(['status']),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Convocation::class);

        return $this->secretariaView('convocations.create', [
            'convocation' => new Convocation,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Convocation::class);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'assembly_at' => ['required', 'date'],
            'notice_days' => ['required', 'integer', 'min:1', 'max:120'],
            'body' => ['nullable', 'string'],
            'meeting_id' => ['nullable', 'exists:secretaria_meetings,id'],
        ]);
        $data['status'] = 'draft';
        $data['created_by_id'] = $request->user()->id;

        $assembly = Carbon::parse($data['assembly_at']);
        $deadline = $assembly->copy()->subDays((int) $data['notice_days']);
        if ($deadline->isPast()) {
            return redirect()->back()->withInput()->with('error', 'A data da assembleia não respeita o aviso prévio de '.$data['notice_days'].' dias (verifique o estatuto).');
        }

        $convocation = Convocation::create($data);

        return redirect()->route($this->routePrefix().'.edit', $convocation)->with('success', 'Convocatória em rascunho.');
    }

    public function show(Convocation $convocation)
    {
        $this->authorize('view', $convocation);
        $convocation->load(['meeting', 'creator', 'approvedBy']);

        return $this->secretariaView('convocations.show', [
            'convocation' => $convocation,
        ]);
    }

    public function edit(Convocation $convocation)
    {
        $this->authorize('update', $convocation);

        return $this->secretariaView('convocations.edit', [
            'convocation' => $convocation,
        ]);
    }

    public function update(Request $request, Convocation $convocation)
    {
        $this->authorize('update', $convocation);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'assembly_at' => ['required', 'date'],
            'notice_days' => ['required', 'integer', 'min:1', 'max:120'],
            'body' => ['nullable', 'string'],
            'meeting_id' => ['nullable', 'exists:secretaria_meetings,id'],
        ]);
        $convocation->update($data);

        return redirect()->route($this->routePrefix().'.show', $convocation)->with('success', 'Convocatória atualizada.');
    }

    public function destroy(Convocation $convocation)
    {
        $this->authorize('delete', $convocation);
        $convocation->delete();

        return redirect()->route($this->routePrefix().'.index')->with('success', 'Convocatória eliminada.');
    }

    public function submit(Convocation $convocation)
    {
        $this->authorize('update', $convocation);
        if ($convocation->status !== 'draft') {
            return redirect()->back()->with('error', 'Estado inválido.');
        }
        $convocation->update(['status' => 'pending_approval']);

        return redirect()->back()->with('success', 'Enviada para aprovação.');
    }

    public function approve(Request $request, Convocation $convocation)
    {
        $this->authorize('approve', $convocation);
        $convocation->update([
            'status' => 'approved',
            'approved_by_id' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Aprovada.');
    }

    public function publish(Request $request, Convocation $convocation)
    {
        $this->authorize('publish', $convocation);
        $convocation->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        $fresh = $convocation->fresh();

        if (config('secretaria.integrations.notificacoes_on_publish', true)) {
            SecretariaNotificationDispatcher::convocationPublished($fresh);
        }

        SecretariaIntegrationBus::afterConvocationPublished($fresh, $request->user());

        return redirect()->back()->with('success', 'Convocatória publicada.');
    }
}
