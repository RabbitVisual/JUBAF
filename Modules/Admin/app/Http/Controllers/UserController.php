<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Modules\Notifications\App\Services\InAppNotificationService;

class UserController extends Controller
{
    public function __construct(
        protected InAppNotificationService $inAppNotificationService
    ) {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'church'])->withCount('relationships');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        if ($request->filled('role_slug')) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('slug', $request->input('role_slug'))
                    ->orWhere('name', $request->input('role_slug'));
            });
        }

        if ($request->filled('church_id')) {
            $query->where('church_id', $request->integer('church_id'));
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $users = $query->paginate(15);

        // Estatísticas
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'by_role' => User::select('role_id', DB::raw('count(*) as total'))
                ->groupBy('role_id')
                ->with('role')
                ->get(),
        ];

        $roles = Role::all();

        $churches = class_exists(\Modules\Church\Models\Church::class)
            ? \Modules\Church\Models\Church::query()->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('admin::users.index', compact('users', 'stats', 'roles', 'churches'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $churches = class_exists(\Modules\Church\Models\Church::class)
            ? \Modules\Church\Models\Church::query()->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('admin::users.create', compact('roles', 'churches'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $churchIdRules = Schema::hasTable('churches')
            ? ['nullable', 'integer', Rule::exists('churches', 'id')]
            : ['nullable'];

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'cpf' => 'nullable|string|max:14|unique:users,cpf',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'marital_status' => 'nullable|in:solteiro,casado,divorciado,viuvo,uniao_estavel',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'address_complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'church_id' => $churchIdRules,
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        if (! Schema::hasTable('churches')) {
            unset($validated['church_id']);
        } else {
            $validated['church_id'] = ! empty($validated['church_id']) ? (int) $validated['church_id'] : null;
        }

        // Gera nome completo
        $validated['name'] = trim($validated['first_name'].' '.$validated['last_name']);

        // Hash da senha
        $validated['password'] = Hash::make($validated['password']);

        // Upload de foto
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('users/photos', 'public');
        }

        // Remove campos não necessários
        unset($validated['password_confirmation']);

        $user = User::create($validated);

        $this->inAppNotificationService->sendToAdmins('Novo membro cadastrado', "O membro {$user->name} foi cadastrado no sistema.", [
            'type' => 'info',
            'priority' => 'normal',
            'action_url' => route('admin.users.show', $user),
            'action_text' => 'Ver perfil',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Membro criado com sucesso!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load([
            'role',
            'church',
            'financialEntries' => function ($q) {
                $q->orderBy('entry_date', 'desc')->limit(20);
            },
        ]);

        return view('admin::users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load(['role']);
        $churches = class_exists(\Modules\Church\Models\Church::class)
            ? \Modules\Church\Models\Church::query()->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('admin::users.edit', compact('user', 'roles', 'churches'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $churchIdRules = Schema::hasTable('churches')
            ? ['nullable', 'integer', Rule::exists('churches', 'id')]
            : ['nullable'];

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'cpf' => ['nullable', 'string', 'max:14', Rule::unique('users')->ignore($user->id)],
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'marital_status' => 'nullable|in:solteiro,casado,divorciado,viuvo,uniao_estavel',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'cellphone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:20',
            'address_complement' => 'nullable|string|max:100',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'church_id' => $churchIdRules,
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        if (! Schema::hasTable('churches')) {
            unset($validated['church_id']);
        } else {
            $validated['church_id'] = ! empty($validated['church_id']) ? (int) $validated['church_id'] : null;
        }

        // Gera nome completo
        $validated['name'] = trim($validated['first_name'].' '.$validated['last_name']);

        // Hash da senha se fornecida
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Upload de foto
        if ($request->hasFile('photo')) {
            // Remove foto antiga
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('users/photos', 'public');
        }

        // Remove campos não necessários
        unset($validated['password_confirmation']);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Membro atualizado com sucesso!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Não permite deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode excluir seu próprio usuário!');
        }

        // Remove foto se existir
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Membro excluído com sucesso!');
    }
}
