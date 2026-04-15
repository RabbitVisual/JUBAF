<?php

namespace Modules\Gateway\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\GatewayAuditLogger;

class GatewayProviderAccountController extends Controller
{
    public function __construct(
        private readonly GatewayAuditLogger $audit,
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', GatewayProviderAccount::class);

        $accounts = GatewayProviderAccount::query()->orderBy('name')->paginate(20);

        return view('gateway::admin.accounts.index', [
            'layout' => 'layouts.app',
            'accounts' => $accounts,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', GatewayProviderAccount::class);

        return view('gateway::admin.accounts.create', [
            'layout' => 'layouts.app',
            'drivers' => GatewayProviderAccount::drivers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', GatewayProviderAccount::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'driver' => ['required', 'string', 'max:32'],
            'base_url' => ['nullable', 'string', 'max:255'],
            'is_enabled' => ['sometimes', 'boolean'],
            'is_default' => ['sometimes', 'boolean'],
            'credentials_json' => ['required', 'string'],
        ]);

        $credentials = json_decode($data['credentials_json'], true);
        if (! is_array($credentials)) {
            return back()->withErrors(['credentials_json' => 'JSON de credenciais inválido.'])->withInput();
        }

        if (! empty($data['is_default'])) {
            GatewayProviderAccount::query()->update(['is_default' => false]);
        }

        $account = GatewayProviderAccount::query()->create([
            'name' => $data['name'],
            'driver' => $data['driver'],
            'base_url' => $data['base_url'] ?? null,
            'is_enabled' => $request->boolean('is_enabled', true),
            'is_default' => $request->boolean('is_default', false),
            'credentials' => $credentials,
        ]);

        $this->audit->log('gateway.account.created', null, null, ['account_id' => $account->id]);

        return redirect()->route('admin.gateway.accounts.index')
            ->with('success', 'Conta de gateway criada.');
    }

    public function edit(GatewayProviderAccount $account): View
    {
        $this->authorize('update', $account);

        return view('gateway::admin.accounts.edit', [
            'layout' => 'layouts.app',
            'account' => $account,
            'drivers' => GatewayProviderAccount::drivers(),
            'credentials_json' => json_encode($account->credentials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function update(Request $request, GatewayProviderAccount $account): RedirectResponse
    {
        $this->authorize('update', $account);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'driver' => ['required', 'string', 'max:32'],
            'base_url' => ['nullable', 'string', 'max:255'],
            'is_enabled' => ['sometimes', 'boolean'],
            'is_default' => ['sometimes', 'boolean'],
            'credentials_json' => ['required', 'string'],
        ]);

        $credentials = json_decode($data['credentials_json'], true);
        if (! is_array($credentials)) {
            return back()->withErrors(['credentials_json' => 'JSON de credenciais inválido.'])->withInput();
        }

        if (! empty($data['is_default'])) {
            GatewayProviderAccount::query()->where('id', '!=', $account->id)->update(['is_default' => false]);
        }

        $account->update([
            'name' => $data['name'],
            'driver' => $data['driver'],
            'base_url' => $data['base_url'] ?? null,
            'is_enabled' => $request->boolean('is_enabled', true),
            'is_default' => $request->boolean('is_default', false),
            'credentials' => $credentials,
        ]);

        $this->audit->log('gateway.account.updated', null, null, ['account_id' => $account->id]);

        return redirect()->route('admin.gateway.accounts.index')
            ->with('success', 'Conta actualizada.');
    }

    public function destroy(GatewayProviderAccount $account): RedirectResponse
    {
        $this->authorize('delete', $account);
        $account->delete();

        return redirect()->route('admin.gateway.accounts.index')
            ->with('success', 'Conta removida.');
    }
}
