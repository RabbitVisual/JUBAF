<?php

namespace Modules\Governance\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Governance\Models\AgendaItem;
use Modules\Governance\Models\Assembly;
use Modules\Governance\Models\Minute;

class AssemblyController extends Controller
{
    public function index()
    {
        $assemblies = Assembly::query()->with('minute')->orderByDesc('scheduled_at')->paginate(15);

        return view('governance::admin.assemblies.index', compact('assemblies'));
    }

    public function create()
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);

        return view('governance::admin.assemblies.create');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.governance.assemblies.show', $assembly)
            ->with('success', 'Assembleia registada.');
    }

    public function show(Assembly $assembly)
    {
        $assembly->load(['agendaItems', 'minute']);

        return view('governance::admin.assemblies.show', compact('assembly'));
    }

    public function edit(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $assembly->load('agendaItems');

        return view('governance::admin.assemblies.edit', compact('assembly'));
    }

    public function update(Request $request, Assembly $assembly)
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

        return redirect()->route('admin.governance.assemblies.show', $assembly)
            ->with('success', 'Assembleia atualizada.');
    }

    public function destroy(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $assembly->delete();

        return redirect()->route('admin.governance.assemblies.index')
            ->with('success', 'Assembleia removida.');
    }
}
