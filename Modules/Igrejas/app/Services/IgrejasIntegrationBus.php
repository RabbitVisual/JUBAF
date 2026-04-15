<?php

namespace Modules\Igrejas\App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;

/**
 * Integrações opcionais entre Igrejas e outros módulos (config igrejas.integrations).
 */
final class IgrejasIntegrationBus
{
    public static function afterRequestSubmitted(ChurchChangeRequest $request): void
    {
        if (! (bool) config('igrejas.integrations.notify_directorate_on_request_submit', true)) {
            return;
        }

        if (! module_enabled('Notificacoes') || ! Schema::hasTable('notifications')) {
            return;
        }

        try {
            $service = app(\Modules\Notificacoes\App\Services\NotificacaoService::class);
            $ids = User::query()
                ->permission('igrejas.requests.review')
                ->pluck('id')
                ->unique();

            if ($ids->isEmpty()) {
                $ids = User::role(['presidente', 'secretario-1', 'secretario-2'])->pluck('id')->unique();
            }

            $url = route('diretoria.igrejas.requests.show', $request, false);
            foreach ($ids as $uid) {
                $service->sendToUser(
                    userId: (int) $uid,
                    type: 'igrejas_pedido',
                    title: 'Novo pedido de alteração (Igrejas)',
                    message: 'Pedido #'.$request->id.' ('.$request->type.') aguarda análise.',
                    actionUrl: $url,
                    data: ['request_id' => $request->id],
                    moduleSource: 'Igrejas',
                    entityType: 'church_change_request',
                    entityId: $request->id,
                    panel: 'diretoria'
                );
            }
        } catch (\Throwable $e) {
            Log::warning('IgrejasIntegrationBus::afterRequestSubmitted falhou.', ['exception' => $e->getMessage()]);
        }
    }

    public static function afterRequestResolved(ChurchChangeRequest $request, string $outcome, User $reviewer): void
    {
        if (! module_enabled('Notificacoes') || ! Schema::hasTable('notifications')) {
            return;
        }

        if (! (bool) config('igrejas.integrations.notify_submitter_on_request_resolve', true)) {
            return;
        }

        if (! $request->submitted_by) {
            return;
        }

        try {
            $service = app(\Modules\Notificacoes\App\Services\NotificacaoService::class);
            $verb = $outcome === 'approved' ? 'aprovado' : 'recusado';
            $service->sendToUser(
                userId: (int) $request->submitted_by,
                type: 'igrejas_pedido_'.$outcome,
                title: 'Pedido de igreja '.$verb,
                message: 'O pedido #'.$request->id.' foi '.$verb.'. '.Str::limit((string) $request->review_notes, 120),
                actionUrl: route('lideres.igrejas.requests.show', $request, false),
                data: ['request_id' => $request->id, 'outcome' => $outcome],
                moduleSource: 'Igrejas',
                entityType: 'church_change_request',
                entityId: $request->id,
                panel: 'lideres'
            );
        } catch (\Throwable $e) {
            Log::warning('IgrejasIntegrationBus::afterRequestResolved falhou.', ['exception' => $e->getMessage()]);
        }
    }

    public static function draftAvisoOnChurchActivated(Church $church, User $actor): void
    {
        if (! (bool) config('igrejas.integrations.aviso_draft_on_church_activated', false)) {
            return;
        }

        if (! module_enabled('Avisos') || ! Schema::hasTable('avisos')) {
            return;
        }

        if (! $actor->can('avisos.create')) {
            return;
        }

        \Modules\Avisos\App\Models\Aviso::query()->create([
            'titulo' => '[Rascunho] Congregação ativa: '.$church->name,
            'descricao' => 'Gerado pelo módulo Igrejas.',
            'conteudo' => '<p>Congregação <strong>'.e($church->name).'</strong> encontra-se ativa no cadastro JUBAF.</p>',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => false,
            'destacar' => false,
            'church_ids' => [(int) $church->id],
            'user_id' => $actor->id,
        ]);
    }
}
