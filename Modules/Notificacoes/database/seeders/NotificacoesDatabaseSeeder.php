<?php

namespace Modules\Notificacoes\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notificacoes\App\Services\NotificacaoService;
use App\Models\User;

class NotificacoesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = app(NotificacaoService::class);

        // Criar notificações de demonstração para todos os usuários
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Crie usuários primeiro.');
            return;
        }

        $notificationsCreated = 0;

        foreach ($users as $user) {
            try {
                // Carregar relacionamentos necessários
                $user->load('roles');

                // Notificação de sucesso
                $dashboardUrl = null;
                if (\Illuminate\Support\Facades\Route::has('notificacoes.index')) {
                    $dashboardUrl = route('notificacoes.index');
                }

                $service->sendToUser(
                    $user->id,
                    'success',
                    'Bem-vindo às notificações JUBAF',
                    'Este é um exemplo de aviso de sucesso. O centro de notificações está ativo e integrado ao teu painel.',
                    $dashboardUrl,
                    ['demo' => true],
                    'Notificacoes',
                    'User',
                    $user->id
                );
                $notificationsCreated++;

                // Notificação de informação
                $dashboardRoute = null;
                $dashName = get_dashboard_route($user);
                if (\Illuminate\Support\Facades\Route::has($dashName)) {
                    $dashboardRoute = route($dashName);
                } elseif (\Illuminate\Support\Facades\Route::has('login')) {
                    $dashboardRoute = route('login');
                }

                $service->sendToUser(
                    $user->id,
                    'info',
                    'Notificações JUBAF ativas',
                    'Receberás aqui avisos importantes sobre o teu perfil, módulos e atividades na plataforma.',
                    $dashboardRoute,
                    ['demo' => true],
                    'Notificacoes',
                    'System',
                    null
                );
                $notificationsCreated++;

                // Notificação de aviso
                $profileRoute = null;
                $profileName = get_profile_route($user);
                if (\Illuminate\Support\Facades\Route::has($profileName)) {
                    $profileRoute = route($profileName);
                } elseif (\Illuminate\Support\Facades\Route::has('login')) {
                    $profileRoute = route('login');
                }

                $service->sendToUser(
                    $user->id,
                    'warning',
                    'Atualiza o teu perfil',
                    'Mantém os teus dados atualizados para comunicações e acesso aos módulos JUBAF.',
                    $profileRoute,
                    ['demo' => true],
                    'Notificacoes',
                    'User',
                    $user->id
                );
                $notificationsCreated++;

                // Notificação de sistema
                $service->sendToUser(
                    $user->id,
                    'system',
                    'Manutenção programada (exemplo)',
                    'Este é um aviso de exemplo sobre janelas de manutenção. A equipa informará datas reais com antecedência.',
                    null,
                    ['demo' => true, 'maintenance_date' => now()->addDays(3)->format('Y-m-d H:i')],
                    'Notificacoes',
                    'System',
                    null
                );
                $notificationsCreated++;

            } catch (\Exception $e) {
                $this->command->error("Erro ao criar notificações para o usuário {$user->id} ({$user->name}): " . $e->getMessage());
                continue;
            }
        }

        $this->command->info("Notificações de demonstração criadas com sucesso!");
        $this->command->info("Total de usuários processados: {$users->count()}");
        $this->command->info("Total de notificações criadas: {$notificationsCreated}");
    }
}
