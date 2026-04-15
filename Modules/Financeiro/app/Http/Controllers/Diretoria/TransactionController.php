<?php

namespace Modules\Financeiro\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Financeiro\App\Http\Requests\StoreFinTransactionRequest;
use Modules\Financeiro\App\Http\Requests\UpdateFinTransactionRequest;
use Modules\Financeiro\App\Models\FinBankAccount;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Financeiro\App\Services\FinanceiroService;
use Modules\Igrejas\App\Models\Church;
use Modules\Secretaria\App\Models\Minute;

class TransactionController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection<int, Minute>
     */
    protected function minutesForTransactionForm(Request $request): \Illuminate\Support\Collection
    {
        if (! module_enabled('Secretaria') || ! class_exists(Minute::class)) {
            return collect();
        }

        $q = Minute::query()
            ->whereIn('status', ['published', 'archived'])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(200);
        if ($request->user()) {
            ErpChurchScope::applyToSecretariaMinuteQuery($q, $request->user());
        }

        return $q->get(['id', 'title', 'protocol_number']);
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', FinTransaction::class);

        $q = FinTransaction::query()->with(['category', 'church', 'creator', 'secretariaMinute', 'bankAccount']);
        $user = $request->user();
        if ($user) {
            ErpChurchScope::applyToFinTransactionQuery($q, $user);
        }

        if ($request->filled('from')) {
            $q->whereDate('occurred_on', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $q->whereDate('occurred_on', '<=', $request->input('to'));
        }
        if ($request->filled('direction')) {
            $q->where('direction', $request->input('direction'));
        }
        if ($request->filled('church_id')) {
            $q->where('church_id', $request->input('church_id'));
        }
        if ($request->filled('scope')) {
            $scope = (string) $request->input('scope');
            if ($scope === FinTransaction::SCOPE_REGIONAL) {
                $q->whereIn('scope', [FinTransaction::SCOPE_REGIONAL, FinTransaction::SCOPE_LEGACY_NACIONAL]);
            } else {
                $q->where('scope', $scope);
            }
        }
        if ($request->filled('source')) {
            $q->where('source', (string) $request->input('source'));
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', (int) $request->input('category_id'));
        }
        if ($request->filled('status')) {
            $q->where('status', (string) $request->input('status'));
        }

        $transactions = $q->orderByDesc('occurred_on')->orderByDesc('id')->paginate(20)->withQueryString();

        $churches = module_enabled('Igrejas')
            ? Church::query()
                ->when($user, fn ($cq) => ErpChurchScope::applyToChurchQuery($cq, $user))
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();

        $filterCategories = FinCategory::query()
            ->where('is_active', true)
            ->orderBy('group_key')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'direction']);

        return view('financeiro::paineldiretoria.transactions.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'transactions' => $transactions,
            'churches' => $churches,
            'filterCategories' => $filterCategories,
            'filters' => $request->only(['from', 'to', 'direction', 'church_id', 'scope', 'source', 'category_id', 'status']),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', FinTransaction::class);

        $user = $request->user();

        return view('financeiro::paineldiretoria.transactions.create', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'categories' => FinCategory::query()
                ->where('is_active', true)
                ->orderBy('group_key')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'bankAccounts' => FinBankAccount::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'churches' => module_enabled('Igrejas')
                ? Church::query()
                    ->where('is_active', true)
                    ->when($user, fn ($cq) => ErpChurchScope::applyToChurchQuery($cq, $user))
                    ->orderBy('name')
                    ->get()
                : collect(),
            'transaction' => new FinTransaction([
                'occurred_on' => now()->toDateString(),
                'scope' => FinTransaction::SCOPE_REGIONAL,
                'direction' => 'in',
                'status' => FinTransaction::STATUS_PAID,
            ]),
            'minutes' => $this->minutesForTransactionForm($request),
        ]);
    }

    public function store(StoreFinTransactionRequest $request, FinanceiroService $financeiro): RedirectResponse
    {
        $data = $request->validated();
        unset($data['comprovante']);
        if ($request->hasFile('comprovante')) {
            $data['comprovante_path'] = $financeiro->storeComprovante($request->file('comprovante'));
        }
        $data['source'] = FinTransaction::SOURCE_MANUAL;
        if ($data['scope'] === FinTransaction::SCOPE_REGIONAL) {
            $data['church_id'] = null;
        }

        $financeiro->createManualTransaction($data, $request->user());

        return redirect()
            ->route('diretoria.financeiro.transactions.index')
            ->with('success', 'Lançamento registado.');
    }

    public function edit(Request $request, FinTransaction $transaction): View|RedirectResponse
    {
        $this->authorize('viewAny', FinTransaction::class);
        if (! $request->user()?->can('update', $transaction)) {
            return redirect()
                ->route('diretoria.financeiro.transactions.index')
                ->with('warning', 'Este lançamento está bloqueado (Gateway ou reconciliado) e não pode ser editado aqui.');
        }

        $user = $request->user();

        return view('financeiro::paineldiretoria.transactions.edit', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'categories' => FinCategory::query()
                ->where('is_active', true)
                ->orderBy('group_key')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'bankAccounts' => FinBankAccount::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'churches' => module_enabled('Igrejas')
                ? Church::query()
                    ->where('is_active', true)
                    ->when($user, fn ($cq) => ErpChurchScope::applyToChurchQuery($cq, $user))
                    ->orderBy('name')
                    ->get()
                : collect(),
            'transaction' => $transaction,
            'minutes' => $this->minutesForTransactionForm($request),
        ]);
    }

    public function update(UpdateFinTransactionRequest $request, FinTransaction $transaction, FinanceiroService $financeiro): RedirectResponse
    {
        $data = $request->validated();
        unset($data['comprovante']);
        if ($request->hasFile('comprovante')) {
            $financeiro->deleteComprovanteIfExists($transaction->comprovante_path);
            $data['comprovante_path'] = $financeiro->storeComprovante($request->file('comprovante'));
        }
        if ($data['scope'] === FinTransaction::SCOPE_REGIONAL) {
            $data['church_id'] = null;
        }
        $financeiro->updateTransaction($transaction, $data);

        return redirect()
            ->route('diretoria.financeiro.transactions.index')
            ->with('success', 'Lançamento actualizado.');
    }

    public function destroy(FinTransaction $transaction, FinanceiroService $financeiro): RedirectResponse
    {
        $this->authorize('delete', $transaction);
        $financeiro->deleteTransaction($transaction);

        return redirect()
            ->route('diretoria.financeiro.transactions.index')
            ->with('success', 'Lançamento removido.');
    }
}
