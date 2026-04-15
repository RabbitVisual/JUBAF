<?php

namespace Modules\Secretaria\App\Services;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Modules\Secretaria\App\Models\Convocation;
use Modules\Secretaria\App\Models\Minute;

/**
 * Integração com Notificacoes. Outros módulos (Avisos, Blog, Calendario, Financeiro)
 * podem subscrever eventos semelhantes no futuro — ver config secretaria.
 */
final class SecretariaNotificationDispatcher
{
    public static function minutePublished(Minute $minute): void
    {
        if (! module_enabled('Notificacoes')) {
            return;
        }

        $title = 'Nova ata publicada';
        $message = $minute->title;

        $roleNames = array_merge(
            JubafRoleRegistry::directorateRoleNames(),
            ['lider']
        );

        $filterLiderByChurch = (bool) config('secretaria.integrations.notificacoes_filter_lider_by_church', true);

        User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $roleNames))
            ->cursor()
            ->each(function (User $user) use ($title, $message, $minute, $filterLiderByChurch) {
                if ($filterLiderByChurch && $minute->church_id
                    && $user->hasRole('lider')
                    && ! $user->hasAnyRole(JubafRoleRegistry::directorateRoleNames())) {
                    if (! in_array((int) $minute->church_id, $user->churchIdsForSecretariaScope(), true)) {
                        return;
                    }
                }

                $panel = 'diretoria';
                $url = route('diretoria.secretaria.atas.show', $minute, false);
                if ($user->hasRole('lider') && ! $user->hasAnyRole(JubafRoleRegistry::directorateRoleNames())) {
                    $panel = 'lideres';
                    $url = route('lideres.secretaria.atas.show', $minute, false);
                }

                \Modules\Notificacoes\App\Models\Notificacao::createNotification(
                    'info',
                    $title,
                    $message,
                    $user->id,
                    null,
                    ['minute_id' => $minute->id],
                    $url,
                    'Secretaria',
                    Minute::class,
                    $minute->id,
                    $panel
                );
            });
    }

    public static function convocationPublished(Convocation $convocation): void
    {
        if (! module_enabled('Notificacoes')) {
            return;
        }

        $title = 'Convocatória publicada';
        $message = $convocation->title.' — '.$convocation->assembly_at->format('d/m/Y H:i');

        $roleNames = array_merge(
            JubafRoleRegistry::directorateRoleNames(),
            ['lider', 'jovens']
        );

        User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $roleNames))
            ->cursor()
            ->each(function (User $user) use ($title, $message, $convocation) {
                $directorate = $user->hasAnyRole(JubafRoleRegistry::directorateRoleNames());
                if ($user->hasRole('jovens')) {
                    $panel = 'jovens';
                    $url = route('jovens.secretaria.convocatorias.show', $convocation, false);
                } elseif ($user->hasRole('lider') && ! $directorate) {
                    $panel = 'lideres';
                    $url = route('lideres.secretaria.convocatorias.show', $convocation, false);
                } else {
                    $panel = 'diretoria';
                    $url = route('diretoria.secretaria.convocatorias.show', $convocation, false);
                }

                \Modules\Notificacoes\App\Models\Notificacao::createNotification(
                    'alert',
                    $title,
                    $message,
                    $user->id,
                    null,
                    ['convocation_id' => $convocation->id],
                    $url,
                    'Secretaria',
                    Convocation::class,
                    $convocation->id,
                    $panel
                );
            });
    }
}
