<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApiManagementController extends Controller
{
    /**
     * Lista todos os tokens de API
     */
    public function index(Request $request)
    {
        $tokens = ApiToken::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Estatísticas
        $stats = [
            'total' => ApiToken::count(),
            'ativos' => ApiToken::active()->count(),
            'inativos' => ApiToken::where('is_active', false)->count(),
        ];

        return view('admin::api.index', compact('tokens', 'stats'));
    }

    /**
     * Mostra formulário de criação
     */
    public function create()
    {
        $users = User::where('active', true)->orderBy('name')->get();
        return view('admin::api.create', compact('users'));
    }

    /**
     * Cria um novo token de API
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'abilities' => 'nullable|array',
            'expires_at' => 'nullable|date|after:now',
            'ip_whitelist' => 'nullable|string',
            'rate_limit' => 'nullable|integer|min:1|max:1000',
        ]);

        // Criar token usando Sanctum
        $user = User::findOrFail($validated['user_id']);
        $abilities = $validated['abilities'] ?? ['*'];
        
        // Criar token Sanctum
        $sanctumToken = $user->createToken($validated['name'], $abilities);
        $plainTextToken = $sanctumToken->plainTextToken;
        
        // Extrair hash do token Sanctum (formato: id|hash)
        $tokenParts = explode('|', $plainTextToken);
        $tokenHash = count($tokenParts) > 1 ? hash('sha256', $tokenParts[1]) : hash('sha256', $plainTextToken);
        
        // Criar registro de metadados na tabela api_tokens
        $apiToken = ApiToken::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'token' => $plainTextToken,
            'token_hash' => $tokenHash,
            'abilities' => $abilities,
            'expires_at' => $validated['expires_at'] ?? null,
            'ip_whitelist' => !empty($validated['ip_whitelist']) 
                ? array_filter(array_map('trim', explode(',', $validated['ip_whitelist'])))
                : null,
            'rate_limit' => $validated['rate_limit'] ?? 60,
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);
        
        $token = $plainTextToken;

        return redirect()->route('admin.api.show', $apiToken->id)
            ->with('success', 'Token criado com sucesso! Guarde o token com segurança: ' . $token);
    }

    /**
     * Mostra detalhes de um token
     */
    public function show($id)
    {
        $token = ApiToken::with('user')->findOrFail($id);
        return view('admin::api.show', compact('token'));
    }

    /**
     * Atualiza um token
     */
    public function update(Request $request, $id)
    {
        $token = ApiToken::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'abilities' => 'nullable|array',
            'expires_at' => 'nullable|date',
            'ip_whitelist' => 'nullable|string',
            'rate_limit' => 'nullable|integer|min:1|max:1000',
            'is_active' => 'boolean',
        ]);

        $token->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'abilities' => $validated['abilities'] ?? $token->abilities,
            'expires_at' => $validated['expires_at'] ?? null,
            'ip_whitelist' => !empty($validated['ip_whitelist']) 
                ? array_filter(array_map('trim', explode(',', $validated['ip_whitelist'])))
                : null,
            'rate_limit' => $validated['rate_limit'] ?? $token->rate_limit,
            'is_active' => $validated['is_active'] ?? $token->is_active,
        ]);

        return redirect()->route('admin.api.show', $token->id)
            ->with('success', 'Token atualizado com sucesso');
    }

    /**
     * Revoga/desativa um token
     */
    public function revoke($id)
    {
        $token = ApiToken::findOrFail($id);

        // Revogar token Sanctum também (token em personal_access_tokens já é hash SHA-256)
        DB::table('personal_access_tokens')
            ->where('tokenable_id', $token->user_id)
            ->where('tokenable_type', User::class)
            ->where('token', $token->token_hash)
            ->delete();
        
        $token->update(['is_active' => false]);

        return redirect()->route('admin.api.index')
            ->with('success', 'Token revogado com sucesso');
    }

    /**
     * Remove um token
     */
    public function destroy($id)
    {
        $token = ApiToken::findOrFail($id);

        // Revogar token Sanctum também (token em personal_access_tokens já é hash SHA-256)
        DB::table('personal_access_tokens')
            ->where('tokenable_id', $token->user_id)
            ->where('tokenable_type', User::class)
            ->where('token', $token->token_hash)
            ->delete();
        
        $token->delete();

        return redirect()->route('admin.api.index')
            ->with('success', 'Token removido com sucesso');
    }

    /**
     * Regenera um token (cria novo e desativa o antigo)
     */
    public function regenerate($id)
    {
        $oldToken = ApiToken::findOrFail($id);
        $oldToken->update(['is_active' => false]);

        // Revogar token Sanctum antigo
        DB::table('personal_access_tokens')
            ->where('tokenable_id', $oldToken->user_id)
            ->where('tokenable_type', User::class)
            ->where('token', $oldToken->token_hash)
            ->delete();

        // Criar novo token Sanctum
        $user = User::findOrFail($oldToken->user_id);
        $sanctumToken = $user->createToken($oldToken->name . ' (Regenerado)', $oldToken->abilities ?? ['*']);
        $plainTextToken = $sanctumToken->plainTextToken;
        $tokenParts = explode('|', $plainTextToken);
        $newTokenHash = count($tokenParts) > 1 ? hash('sha256', $tokenParts[1]) : hash('sha256', $plainTextToken);

        $apiToken = ApiToken::create([
            'user_id' => $oldToken->user_id,
            'name' => $oldToken->name . ' (Regenerado)',
            'token' => $plainTextToken,
            'token_hash' => $newTokenHash,
            'abilities' => $oldToken->abilities,
            'expires_at' => $oldToken->expires_at,
            'ip_whitelist' => $oldToken->ip_whitelist,
            'rate_limit' => $oldToken->rate_limit,
            'description' => $oldToken->description,
            'is_active' => true,
        ]);

        return redirect()->route('admin.api.show', $apiToken->id)
            ->with('success', 'Token regenerado com sucesso! Novo token: ' . $plainTextToken);
    }
}

