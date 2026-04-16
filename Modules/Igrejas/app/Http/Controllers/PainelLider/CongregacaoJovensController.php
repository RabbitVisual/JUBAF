<?php

namespace Modules\Igrejas\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Igrejas\App\Http\Requests\PainelLider\StoreYouthMemberRequest;
use Modules\Igrejas\App\Http\Requests\PainelLider\UpdateYouthMemberRequest;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Services\LeaderYouthProvisioningService;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function exportCsv(Request $request): StreamedResponse|RedirectResponse
    {
        $this->authorize('igrejasProvisionYouth');

        $leader = $request->user();
        if (! $leader?->church_id) {
            return redirect()
                ->route('lideres.congregacao.index')
                ->with('error', 'A tua conta precisa de uma igreja associada.');
        }

        $church = Church::query()->find($leader->church_id);
        if ($church) {
            $this->authorize('view', $church);
        }

        AuditLog::log(
            'igrejas.lider.youth_export',
            Church::class,
            (int) $leader->church_id,
            'igrejas',
            'Exportação CSV de jovens (painel do líder).',
            null,
            null
        );

        $churchId = (int) $leader->church_id;
        $filename = 'congregacao-'.$churchId.'-jovens-'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $cols = [
            'name',
            'email',
            'phone',
            'active',
            'provisioned_at',
            'provisioned_by_email',
            'access_pending',
            'email_verified_at',
        ];

        return response()->streamDownload(function () use ($cols, $churchId) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $cols);
            User::query()
                ->role('jovens')
                ->where('church_id', $churchId)
                ->with(['provisionedBy:id,email'])
                ->orderBy('name')
                ->each(function (User $u) use ($out): void {
                    fputcsv($out, [
                        $u->name,
                        $u->email,
                        $u->phone ?? '',
                        $u->active ? '1' : '0',
                        $u->provisioned_at?->format('Y-m-d H:i:s') ?? '',
                        $u->provisionedBy?->email ?? '',
                        $u->hasPendingInvitedAccess() ? '1' : '0',
                        $u->email_verified_at?->format('Y-m-d H:i:s') ?? '',
                    ]);
                });
            fclose($out);
        }, $filename, $headers);
    }
}
