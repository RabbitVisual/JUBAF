<?php

namespace Modules\Igrejas\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Igrejas\App\Models\ChurchChangeRequest;
use Modules\Igrejas\App\Policies\ChurchPolicy;
use Modules\Igrejas\App\Services\ChurchChangeRequestProcessor;
use Modules\Igrejas\App\Services\IgrejasIntegrationBus;

class DiretoriaChurchChangeRequestController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', ChurchChangeRequest::class);

        $q = ChurchChangeRequest::query()
            ->with(['church', 'submitter'])
            ->orderByDesc('updated_at');

        if (! ChurchPolicy::canBrowseAllChurches($request->user())) {
            $q->where('submitted_by', $request->user()->id);
        } elseif ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        $requests = $q->paginate(20)->withQueryString();

        return view('igrejas::paineldiretoria.requests.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.igrejas',
            'requests' => $requests,
            'filters' => $request->only(['status']),
            'isDirectorate' => ChurchPolicy::canBrowseAllChurches($request->user()),
        ]);
    }

    public function show(Request $request, ChurchChangeRequest $churchChangeRequest): View
    {
        $this->authorize('view', $churchChangeRequest);

        $churchChangeRequest->load(['church', 'submitter', 'reviewer']);

        return view('igrejas::paineldiretoria.requests.show', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.igrejas',
            'req' => $churchChangeRequest,
            'canReview' => $request->user()->can('review', $churchChangeRequest),
        ]);
    }

    public function approve(Request $request, ChurchChangeRequest $churchChangeRequest): RedirectResponse
    {
        $this->authorize('review', $churchChangeRequest);

        $request->validate([
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        ChurchChangeRequestProcessor::approve(
            $churchChangeRequest,
            $request->user(),
            $request->input('review_notes')
        );

        IgrejasIntegrationBus::afterRequestResolved($churchChangeRequest->fresh(), 'approved', $request->user());

        return redirect()
            ->route('diretoria.igrejas.requests.show', $churchChangeRequest)
            ->with('success', 'Pedido aprovado e alterações aplicadas.');
    }

    public function reject(Request $request, ChurchChangeRequest $churchChangeRequest): RedirectResponse
    {
        $this->authorize('review', $churchChangeRequest);

        $request->validate([
            'review_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        ChurchChangeRequestProcessor::reject(
            $churchChangeRequest,
            $request->user(),
            $request->input('review_notes')
        );

        IgrejasIntegrationBus::afterRequestResolved($churchChangeRequest->fresh(), 'rejected', $request->user());

        return redirect()
            ->route('diretoria.igrejas.requests.show', $churchChangeRequest)
            ->with('success', 'Pedido recusado.');
    }
}
