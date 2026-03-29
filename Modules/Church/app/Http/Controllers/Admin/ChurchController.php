<?php

namespace Modules\Church\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            $query->where(function ($q) use ($search) {
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

        return view('church::admin.churches.index', compact('churches', 'sectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('church::admin.churches.create', compact('users'));
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

        return redirect()->route('admin.churches.index')
            ->with('success', 'Igreja cadastrada com sucesso!');
    }

    /**
     * Show the specified resource.
     */
    public function show(Church $church)
    {
        $church->loadCount(['users', 'participants']);

        return view('church::admin.churches.show', compact('church'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Church $church)
    {
        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('church::admin.churches.edit', compact('church', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChurchRequest $request, Church $church)
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active', false);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($church->logo_path) {
                Storage::disk('public')->delete($church->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('churches/logos', 'public');
        }

        $church->update($data);

        return redirect()->route('admin.churches.index')
            ->with('success', 'Igreja atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Church $church)
    {
        if ($church->logo_path) {
            Storage::disk('public')->delete($church->logo_path);
        }

        $church->delete();

        return redirect()->route('admin.churches.index')
            ->with('success', 'Igreja removida com sucesso!');
    }
}
