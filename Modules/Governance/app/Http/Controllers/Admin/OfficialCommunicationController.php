<?php

namespace Modules\Governance\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Governance\Models\OfficialCommunication;

class OfficialCommunicationController extends Controller
{
    public function index()
    {
        $items = OfficialCommunication::query()->orderByDesc('updated_at')->paginate(15);

        return view('governance::admin.communications.index', compact('items'));
    }

    public function create()
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);

        return view('governance::admin.communications.create');
    }

    public function store(Request $request)
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

        return redirect()->route('admin.governance.communications.index')
            ->with('success', 'Comunicado criado.');
    }

    public function edit(OfficialCommunication $communication)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);

        return view('governance::admin.communications.edit', compact('communication'));
    }

    public function update(Request $request, OfficialCommunication $communication)
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

        return redirect()->route('admin.governance.communications.index')
            ->with('success', 'Comunicado atualizado.');
    }

    public function destroy(OfficialCommunication $communication)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $communication->delete();

        return redirect()->route('admin.governance.communications.index')
            ->with('success', 'Removido.');
    }
}
