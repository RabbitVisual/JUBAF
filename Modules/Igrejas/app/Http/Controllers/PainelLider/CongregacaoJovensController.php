<?php

namespace Modules\Igrejas\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Igrejas\App\Http\Requests\PainelLider\StoreYouthMemberRequest;
use Modules\Igrejas\App\Http\Requests\PainelLider\UpdateYouthMemberRequest;
use Modules\Igrejas\App\Services\LeaderYouthProvisioningService;

class CongregacaoJovensController extends Controller
{
    public function __construct(
        protected LeaderYouthProvisioningService $provisioning
    ) {}

    public function create(Request $request): View|RedirectResponse
    {
        $this->authorize('igrejasProvisionYouth');

        if (! $request->user()?->church_id) {
            return redirect()
                ->route('lideres.congregacao.index')
                ->with('error', 'A tua conta precisa de uma igreja associada para adicionar jovens.');
        }

        return view('igrejas::painellider.congregacao.jovens.create');
    }

    public function store(StoreYouthMemberRequest $request): RedirectResponse
    {
        $leader = $request->user();
        $this->authorize('igrejasProvisionYouth');

        if (! $leader->church_id) {
            return redirect()
                ->route('lideres.congregacao.index')
                ->with('error', 'A tua conta precisa de uma igreja associada.');
        }

        $validated = $request->validated();
        $validated['set_password_now'] = $request->boolean('set_password_now');

        try {
            $this->provisioning->create($leader, $validated);
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', 'Não foi possível criar o registo. Tente novamente ou contacte o suporte.');
        }

        $msg = $request->boolean('set_password_now')
            ? 'Jovem adicionado. Pode partilhar a palavra-passe com cuidado.'
            : 'Jovem adicionado. Foi enviado um e-mail para definir a palavra-passe (se o correio estiver configurado).';

        return redirect()
            ->route('lideres.congregacao.index')
            ->with('success', $msg);
    }

    public function edit(Request $request, User $youth): View|RedirectResponse
    {
        $this->authorize('igrejasManageChurchYouth', $youth);

        if (! $request->user()?->church_id) {
            return redirect()
                ->route('lideres.congregacao.index')
                ->with('error', 'A tua conta precisa de uma igreja associada.');
        }

        return view('igrejas::painellider.congregacao.jovens.edit', [
            'youth' => $youth,
        ]);
    }

    public function update(UpdateYouthMemberRequest $request, User $youth): RedirectResponse
    {
        $this->authorize('igrejasManageChurchYouth', $youth);

        $leader = $request->user();
        if (! $leader?->church_id) {
            return redirect()
                ->route('lideres.congregacao.index')
                ->with('error', 'A tua conta precisa de uma igreja associada.');
        }

        $validated = $request->validated();
        $validated['active'] = $request->boolean('active');
        $this->provisioning->update($leader, $youth, $validated);

        return redirect()
            ->route('lideres.congregacao.index')
            ->with('success', 'Dados do jovem actualizados.');
    }

    public function sendPasswordReset(Request $request, User $youth): RedirectResponse
    {
        $this->authorize('igrejasManageChurchYouth', $youth);

        try {
            $this->provisioning->sendPasswordResetEmail($youth);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Não foi possível enviar o e-mail. Verifique o endereço ou tente mais tarde.');
        }

        return back()->with('success', 'Se o e-mail existir na conta, foi enviado um link para definir a palavra-passe.');
    }
}
