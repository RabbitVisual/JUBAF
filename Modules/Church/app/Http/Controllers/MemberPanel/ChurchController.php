<?php

namespace Modules\Church\App\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Church\Http\Requests\StoreChurchRequest;
use Modules\Church\Http\Requests\UpdateChurchRequest;
use Modules\Church\Models\Church;

class ChurchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Church::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('unijovem_name', 'like', "%{$search}%")
                  ->orWhere('leader_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sector')) {
            $query->where('sector', $request->sector);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Get unique sectors for filter
        $sectors = Church::whereNotNull('sector')->distinct()->pluck('sector')->sort()->values();

        $churches = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('church::memberpanel.churches.index', compact('churches', 'sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('church::memberpanel.churches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChurchRequest $request)
    {
        $data = $request->validated();
        
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('churches/logos', 'public');
        }

        Church::create($data);

        return redirect()->route('memberpanel.churches.index')
            ->with('success', 'Igreja/Congregação cadastrada com sucesso!');
    }

    /**
     * Show the specified resource.
     */
    public function show(Church $church)
    {
        return view('church::memberpanel.churches.show', compact('church'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Church $church)
    {
        return view('church::memberpanel.churches.edit', compact('church'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChurchRequest $request, Church $church)
    {
        $data = $request->validated();
        
        $data['is_active'] = $request->boolean('is_active', false);

        if ($request->hasFile('logo')) {
            if ($church->logo_path) {
                Storage::disk('public')->delete($church->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('churches/logos', 'public');
        }

        $church->update($data);

        return redirect()->route('memberpanel.churches.index')
            ->with('success', 'Cadastro da Igreja atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage (Only Admin typically deletes, but we allow board members to do so, or just soft delete).
     */
    public function destroy(Church $church)
    {
        if ($church->logo_path) {
            Storage::disk('public')->delete($church->logo_path);
        }
        
        $church->delete();

        return redirect()->route('memberpanel.churches.index')
            ->with('success', 'Igreja removida com sucesso!');
    }
}
