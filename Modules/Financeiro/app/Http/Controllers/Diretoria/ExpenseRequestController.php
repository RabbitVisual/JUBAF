<?php

namespace Modules\Financeiro\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Financeiro\App\Http\Requests\StoreFinExpenseRequestRequest;
use Modules\Financeiro\App\Http\Requests\UpdateFinExpenseRequestRequest;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinExpenseRequest;
use Modules\Financeiro\App\Models\FinTransaction;

class ExpenseRequestController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', FinExpenseRequest::class);

        $q = FinExpenseRequest::query()->with(['requester', 'approver'])->orderByDesc('id');

        if ($request->filled('status')) {
            $q->where('status', $request->input('status'));
        }

        $requests = $q->paginate(20)->withQueryString();

        return view('financeiro::paineldiretoria.expense_requests.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'requests' => $requests,
            'filters' => $request->only(['status']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', FinExpenseRequest::class);

        return view('financeiro::paineldiretoria.expense_requests.create', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
        ]);
    }

    public function store(StoreFinExpenseRequestRequest $request): RedirectResponse
    {
        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('financeiro/expense_requests', 'public');
        }

        FinExpenseRequest::query()->create([
            'amount' => $request->input('amount'),
            'justification' => $request->input('justification'),
            'status' => FinExpenseRequest::STATUS_DRAFT,
            'requested_by' => $request->user()->id,
            'attachment_path' => $path,
        ]);

        return redirect()
            ->route('diretoria.financeiro.expense-requests.index')
            ->with('success', 'Pedido guardado como rascunho.');
    }

    public function edit(FinExpenseRequest $expense_request): View
    {
        $this->authorize('update', $expense_request);

        return view('financeiro::paineldiretoria.expense_requests.edit', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'expenseRequest' => $expense_request,
        ]);
    }

    public function update(UpdateFinExpenseRequestRequest $request, FinExpenseRequest $expense_request): RedirectResponse
    {
        $data = $request->only(['amount', 'justification']);
        if ($request->hasFile('attachment')) {
            if ($expense_request->attachment_path) {
                Storage::disk('public')->delete($expense_request->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('financeiro/expense_requests', 'public');
        }
        $expense_request->update($data);

        return redirect()
            ->route('diretoria.financeiro.expense-requests.index')
            ->with('success', 'Pedido actualizado.');
    }

    public function destroy(FinExpenseRequest $expense_request): RedirectResponse
    {
        $this->authorize('delete', $expense_request);
        if ($expense_request->attachment_path) {
            Storage::disk('public')->delete($expense_request->attachment_path);
        }
        $expense_request->delete();

        return redirect()
            ->route('diretoria.financeiro.expense-requests.index')
            ->with('success', 'Rascunho eliminado.');
    }

    public function submit(Request $request, FinExpenseRequest $expense_request): RedirectResponse
    {
        $this->authorize('submit', $expense_request);
        $expense_request->update(['status' => FinExpenseRequest::STATUS_SUBMITTED]);

        return back()->with('success', 'Pedido submetido para aprovação.');
    }

    public function approve(Request $request, FinExpenseRequest $expense_request): RedirectResponse
    {
        $this->authorize('approve', $expense_request);
        $expense_request->update([
            'status' => FinExpenseRequest::STATUS_APPROVED,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Pedido aprovado. O tesoureiro pode registar o pagamento.');
    }

    public function reject(Request $request, FinExpenseRequest $expense_request): RedirectResponse
    {
        $this->authorize('reject', $expense_request);
        $request->validate(['rejection_reason' => ['required', 'string', 'max:2000']]);
        $expense_request->update([
            'status' => FinExpenseRequest::STATUS_REJECTED,
            'rejection_reason' => $request->input('rejection_reason'),
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Pedido recusado.');
    }

    public function pay(Request $request, FinExpenseRequest $expense_request): RedirectResponse
    {
        $this->authorize('pay', $expense_request);

        $category = FinCategory::query()
            ->where('direction', 'out')
            ->where('code', FinCategory::CODE_DES_REEMBOLSO)
            ->first()
            ?? FinCategory::query()
                ->where('direction', 'out')
                ->where('name', 'like', '%Reembolso%')
                ->first()
            ?? FinCategory::query()->where('direction', 'out')->orderBy('sort_order')->first();

        abort_unless($category, 500, 'Sem categoria de despesa configurada.');

        DB::transaction(function () use ($expense_request, $category, $request): void {
            $tx = FinTransaction::query()->create([
                'category_id' => $category->id,
                'occurred_on' => now()->toDateString(),
                'amount' => $expense_request->amount,
                'direction' => 'out',
                'scope' => FinTransaction::SCOPE_REGIONAL,
                'church_id' => null,
                'description' => 'Reembolso: '.$expense_request->justification,
                'reference' => 'REQ-'.$expense_request->id,
                'source' => FinTransaction::SOURCE_MANUAL,
                'created_by' => $request->user()->id,
            ]);

            $expense_request->update([
                'status' => FinExpenseRequest::STATUS_PAID,
                'paid_transaction_id' => $tx->id,
            ]);
        });

        return back()->with('success', 'Pagamento registado no livro de lançamentos.');
    }
}
