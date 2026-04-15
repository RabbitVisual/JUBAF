<?php

namespace Modules\Igrejas\App\Http\Controllers\Pastor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Policies\ChurchPolicy;

class ChurchDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (ChurchPolicy::canBrowseAllChurches($user)) {
            $this->authorize('viewAny', Church::class);

            $q = Church::query()->withCount(['users', 'jovensMembers', 'leaders']);

            if ($request->filled('search')) {
                $s = $request->string('search');
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', '%'.$s.'%')
                        ->orWhere('city', 'like', '%'.$s.'%')
                        ->orWhere('email', 'like', '%'.$s.'%');
                });
            }

            if ($request->filled('active')) {
                $q->where('is_active', $request->boolean('active'));
            }

            if ($request->filled('city')) {
                $q->where('city', 'like', '%'.$request->string('city').'%');
            }

            $churches = $q->orderBy('name')->paginate(20)->withQueryString();

            $myChurch = $user->church_id
                ? Church::query()->find($user->church_id)
                : null;

            return view('igrejas::pastor.churches.index', [
                'churches' => $churches,
                'filters' => $request->only(['search', 'active', 'city']),
                'myChurch' => $myChurch,
            ]);
        }

        if (! $user->church_id) {
            return view('igrejas::pastor.churches.no-congregation');
        }

        $church = Church::query()->find($user->church_id);
        if (! $church) {
            return view('igrejas::pastor.churches.no-congregation');
        }

        $this->authorize('view', $church);

        return redirect()->route('pastor.igrejas.show', $church);
    }

    public function show(Church $church)
    {
        $this->authorize('view', $church);

        $church->loadCount(['users', 'jovensMembers', 'leaders']);

        $leaders = User::query()
            ->role('lider')
            ->where('church_id', $church->id)
            ->orderBy('name')
            ->get(['name', 'email', 'phone']);

        return view('igrejas::pastor.churches.show', [
            'church' => $church,
            'leaders' => $leaders,
        ]);
    }
}
