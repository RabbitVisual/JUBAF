<?php

namespace Modules\Igrejas\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Igrejas\App\Http\Requests\StoreChurchChangeDraftRequest;
use Modules\Igrejas\App\Http\Requests\UpdateChurchChangeDraftRequest;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;
use Modules\Igrejas\App\Policies\ChurchChangeRequestPolicy;
use Modules\Igrejas\App\Services\IgrejasIntegrationBus;

class ChurchChangeRequestController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', ChurchChangeRequest::class);

        $requests = ChurchChangeRequest::query()
            ->with(['church'])
            ->where('submitted_by', $request->user()->id)
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('igrejas::painellider.requests.index', [
            'requests' => $requests,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', ChurchChangeRequest::class);

        $churches = Church::query()
            ->whereIn('id', $request->user()->affiliatedChurchIds())
            ->orderBy('name')
            ->get();

        return view('igrejas::painellider.requests.create', [
            'churches' => $churches,
            'types' => ChurchChangeRequest::types(),
            'leadershipUsers' => $this->leadershipUserOptions($request->user()),
        ]);
    }

    public function store(StoreChurchChangeDraftRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $payload = $request->normalizedPayload();

        $probe = new ChurchChangeRequest([
            'church_id' => $validated['church_id'] ?? null,
            'type' => $validated['type'],
        ]);
        $probe->submitted_by = $request->user()->id;
        abort_unless(
            ChurchChangeRequestPolicy::userMayActOnRequestChurch($request->user(), $probe),
            403,
            'Sem permissão para este pedido.'
        );

        $req = ChurchChangeRequest::query()->create([
            'church_id' => $validated['church_id'] ?? null,
            'type' => $validated['type'],
            'status' => ChurchChangeRequest::STATUS_DRAFT,
            'payload' => $payload,
            'submitted_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('lideres.igrejas.requests.edit', $req)
            ->with('success', 'Rascunho guardado. Edite o conteúdo e envie para a diretoria.');
    }

    public function show(ChurchChangeRequest $churchChangeRequest): View
    {
        $this->authorize('view', $churchChangeRequest);

        $churchChangeRequest->load(['church', 'reviewer']);

        return view('igrejas::painellider.requests.show', [
            'req' => $churchChangeRequest,
        ]);
    }

    public function edit(ChurchChangeRequest $churchChangeRequest): View
    {
        $this->authorize('update', $churchChangeRequest);

        $churchChangeRequest->loadMissing('church');

        $churches = Church::query()
            ->whereIn('id', $request->user()->affiliatedChurchIds())
            ->orderBy('name')
            ->get();

        return view('igrejas::painellider.requests.edit', [
            'req' => $churchChangeRequest,
            'types' => ChurchChangeRequest::types(),
            'churches' => $churches,
            'leadershipUsers' => $this->leadershipUserOptions($request->user()),
        ]);
    }

    public function update(UpdateChurchChangeDraftRequest $request, ChurchChangeRequest $churchChangeRequest): RedirectResponse
    {
        $churchChangeRequest->update([
            'payload' => $request->normalizedPayload(),
        ]);

        return redirect()
            ->route('lideres.igrejas.requests.edit', $churchChangeRequest)
            ->with('success', 'Rascunho actualizado.');
    }

    public function submit(Request $request, ChurchChangeRequest $churchChangeRequest): RedirectResponse
    {
        $this->authorize('submit', $churchChangeRequest);

        $churchChangeRequest->forceFill([
            'status' => ChurchChangeRequest::STATUS_SUBMITTED,
            'submitted_by' => $request->user()->id,
        ])->save();

        IgrejasIntegrationBus::afterRequestSubmitted($churchChangeRequest->fresh());

        return redirect()
            ->route('lideres.igrejas.requests.show', $churchChangeRequest)
            ->with('success', 'Pedido enviado à diretoria para análise.');
    }

    /**
     * Utilizadores ligados às congregações onde o líder tem função (lista para selects de pastor / Unijovem).
     *
     * @return Collection<int, User>
     */
    protected function leadershipUserOptions(User $user): Collection
    {
        $churchIds = $user->affiliatedChurchIds();
        if ($churchIds === []) {
            return collect();
        }

        $fromPivot = DB::table('user_churches')->whereIn('church_id', $churchIds)->pluck('user_id');
        $fromPrimary = User::query()->whereIn('church_id', $churchIds)->pluck('id');
        $ids = $fromPrimary->merge($fromPivot)->unique()->values();

        return User::query()
            ->whereIn('id', $ids)
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
