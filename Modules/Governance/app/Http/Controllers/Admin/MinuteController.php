<?php

namespace Modules\Governance\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Governance\Models\Assembly;
use Modules\Governance\Models\Minute;

class MinuteController extends Controller
{
    public function edit(Assembly $assembly)
    {
        abort_unless(Auth::user()?->canAccess('governance_manage'), 403);
        $minute = $assembly->minute ?? new Minute([
            'assembly_id' => $assembly->id,
            'body' => '',
            'status' => 'draft',
        ]);

        return view('governance::admin.minutes.edit', compact('assembly', 'minute'));
    }

    public function update(Request $request, Assembly $assembly)
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

        return redirect()->route('admin.governance.assemblies.show', $assembly)
            ->with('success', 'Ata guardada.');
    }

    public function publish(Request $request, Assembly $assembly)
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
}
