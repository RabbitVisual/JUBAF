<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AuditService;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['user_id', 'module', 'action', 'date_from', 'date_to', 'search']);
        $logs = $this->auditService->getAuditLogs($filters);
        $stats = $this->auditService->getAuditStats();
        $users = \App\Models\User::select('id', 'name')->get();
        $modules = \App\Models\AuditLog::select('module')
            ->whereNotNull('module')
            ->distinct()
            ->pluck('module');

        return view('admin::audit.index', compact('logs', 'stats', 'users', 'modules', 'filters'));
    }

    public function show($id)
    {
        $log = \App\Models\AuditLog::with('user')->findOrFail($id);
        return view('admin::audit.show', compact('log'));
    }

    public function clean(Request $request)
    {
        $days = (int) $request->input('days', 90);

        try {
            // Se days for 0, limpa tudo (opcional, vamos ver se o usuário quer isso)
            // Mas seguindo o pedido por "período", vamos manter a lógica de subDays
            $deleted = $this->auditService->cleanOldLogs($days);

            $message = $days === 0
                ? "Todos os logs foram removidos com sucesso."
                : "{$deleted} logs com mais de {$days} dias foram removidos.";

            return redirect()->route('admin.audit.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao limpar logs: ' . $e->getMessage());
        }
    }
}
