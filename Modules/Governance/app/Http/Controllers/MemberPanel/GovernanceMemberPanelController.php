<?php

namespace Modules\Governance\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Governance\Models\AgendaItem;
use Modules\Governance\Models\Assembly;
use Modules\Governance\Models\Minute;
use Modules\Governance\Models\OfficialCommunication;

class GovernanceMemberPanelController extends Controller
{
    public function dashboard()
    {
        abort_unless(Auth::user()?->canAccess('governance_view'), 403);

        $assemblyCount = Assembly::query()->count();
        $communicationCount = OfficialCommunication::query()->count();
        $canManage = Auth::user()?->canAccess('governance_manage') ?? false;

        return view('governance::memberpanel.dashboard', compact('assemblyCount', 'communicationCount', 'canManage'));
    }

    public function assembliesIndex()
    {
        abort_unless(Auth::user()?->canAccess('governance_view'), 403);
        $assemblies = Assembly::query()
            ->with('minute')
            ->orderByDesc('scheduled_at')
            ->paginate(15);

        return view('governance::memberpanel.assemblies.index', compact('assemblies'));
    }

    public function assembliesCreate()
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);

        return view('governance::memberpanel.assemblies.create');
    }

    public function assembliesStore(Request $request)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $data = $request->validate([
            'type' => 'required|in:ordinaria,extraordinaria',
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'convocation_notes' => 'nullable|string',
        ]);
        $data['created_by'] = Auth::id();
        $assembly = Assembly::create($data);

        return redirect()->route('memberpanel.governance.assemblies.show', $assembly)
            ->with('success', 'Assembleia registada.');
    }

    public function assembliesShow(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_view'), 403);
        $assembly->load(['agendaItems', 'minute']);
        $canSeeDraftMinute = Auth::user()?->canAccess('governance_manage') ?? false;
        $canManage = Auth::user()?->canAccess('governance_manage') ?? false;

        return view('governance::memberpanel.assemblies.show', compact('assembly', 'canSeeDraftMinute', 'canManage'));
    }

    public function assembliesEdit(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $assembly->load('agendaItems');

        return view('governance::memberpanel.assemblies.edit', compact('assembly'));
    }

    public function assembliesUpdate(Request $request, Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $data = $request->validate([
            'type' => 'required|in:ordinaria,extraordinaria',
            'title' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'convocation_notes' => 'nullable|string',
        ]);
        $assembly->update($data);

        if ($request->filled('agenda_titles')) {
            $assembly->agendaItems()->delete();
            foreach ($request->input('agenda_titles', []) as $i => $title) {
                $title = trim((string) $title);
                if ($title === '') {
                    continue;
                }
                AgendaItem::create([
                    'assembly_id' => $assembly->id,
                    'sort_order' => $i,
                    'title' => $title,
                    'description' => $request->input('agenda_descriptions.'.$i),
                ]);
            }
        }

        return redirect()->route('memberpanel.governance.assemblies.show', $assembly)
            ->with('success', 'Assembleia atualizada.');
    }

    public function assembliesDestroy(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $assembly->delete();

        return redirect()->route('memberpanel.governance.assemblies.index')
            ->with('success', 'Assembleia removida.');
    }

    public function assembliesMinuteEdit(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $minute = $assembly->minute ?? new Minute([
            'assembly_id' => $assembly->id,
            'body' => '',
            'status' => 'draft',
        ]);

        return view('governance::memberpanel.minutes.edit', compact('assembly', 'minute'));
    }

    public function assembliesMinuteUpdate(Request $request, Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $data = $request->validate([
            'body' => 'required|string',
            'status' => 'required|in:draft,approved,published',
        ]);
        $minute = Minute::query()->firstOrNew(['assembly_id' => $assembly->id]);
        $minute->fill($data);
        $minute->created_by = $minute->created_by ?? Auth::id();
        if ($data['status'] === 'published' && ! $minute->published_at) {
            $minute->published_at = now();
        }
        if ($data['status'] !== 'published') {
            $minute->published_at = null;
        }
        $minute->save();

        return redirect()->route('memberpanel.governance.assemblies.show', $assembly)
            ->with('success', 'Ata guardada.');
    }

    public function assembliesMinutePublish(Request $request, Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $minute = $assembly->minute;
        abort_unless($minute, 404);
        $minute->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return back()->with('success', 'Ata publicada no site.');
    }

    public function communicationsIndex()
    {
        abort_unless(Auth::user()?->canAccess('governance_view'), 403);
        $user = Auth::user();
        $q = OfficialCommunication::query()->orderByDesc('updated_at');
        if (! $user?->canAccess('governance_manage')) {
            $q->where('is_published', true)->whereNotNull('published_at');
        }
        $items = $q->paginate(15);

        return view('governance::memberpanel.communications.index', compact('items'));
    }

    public function communicationsCreate()
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);

        return view('governance::memberpanel.communications.create');
    }

    public function communicationsStore(Request $request)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'body' => 'required|string',
            'is_published' => 'boolean',
        ]);
        $data['created_by'] = Auth::id();
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = now();
        }
        OfficialCommunication::create($data);

        return redirect()->route('memberpanel.governance.communications.index')
            ->with('success', 'Comunicado criado.');
    }

    public function communicationsShow(OfficialCommunication $communication)
    {
        abort_unless(Auth::user()?->canAccess('governance_view'), 403);
        $user = Auth::user();
        if (! $communication->is_published || ! $communication->published_at) {
            abort_unless($user?->canAccess('governance_manage'), 403);
        }
        $canManage = $user?->canAccess('governance_manage') ?? false;

        return view('governance::memberpanel.communications.show', compact('communication', 'canManage'));
    }

    public function communicationsEdit(OfficialCommunication $communication)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);

        return view('governance::memberpanel.communications.edit', compact('communication'));
    }

    public function communicationsUpdate(Request $request, OfficialCommunication $communication)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'body' => 'required|string',
            'is_published' => 'boolean',
        ]);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? ($communication->published_at ?? now()) : null;
        $communication->update($data);

        return redirect()->route('memberpanel.governance.communications.index')
            ->with('success', 'Comunicado atualizado.');
    }

    public function communicationsDestroy(OfficialCommunication $communication)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $communication->delete();

        return redirect()->route('memberpanel.governance.communications.index')
            ->with('success', 'Removido.');
    }
}
