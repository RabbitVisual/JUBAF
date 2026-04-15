<?php

namespace Modules\Chat\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Chat\App\Models\ChatConfig;

class ChatConfigSeeder extends Seeder
{
    public function run(): void
    {
        $defaultHours = json_encode([
            'monday' => ['enabled' => true, 'start' => '08:00', 'end' => '17:00'],
            'tuesday' => ['enabled' => true, 'start' => '08:00', 'end' => '17:00'],
            'wednesday' => ['enabled' => true, 'start' => '08:00', 'end' => '17:00'],
            'thursday' => ['enabled' => true, 'start' => '08:00', 'end' => '17:00'],
            'friday' => ['enabled' => true, 'start' => '08:00', 'end' => '17:00'],
            'saturday' => ['enabled' => false, 'start' => '08:00', 'end' => '12:00'],
            'sunday' => ['enabled' => false, 'start' => '08:00', 'end' => '12:00'],
        ]);

        $configs = [
            ['key' => 'chat_enabled', 'value' => 'true', 'description' => 'Chat habilitado no sistema'],
            ['key' => 'public_chat_enabled', 'value' => 'true', 'description' => 'Chat público habilitado na homepage'],
            ['key' => 'public_availability_mode', 'value' => 'either', 'description' => 'office_hours | agents_online | either'],
            ['key' => 'opening_hours', 'value' => $defaultHours, 'description' => 'Horários de funcionamento do chat'],
            ['key' => 'welcome_message', 'value' => 'Olá! Bem-vindo ao apoio JUBAF. Em que podemos ajudar?', 'description' => 'Mensagem de boas-vindas do chat'],
            ['key' => 'offline_message', 'value' => 'No momento não há atendimento pelo chat. Tente novamente no horário configurado ou aguarde um membro da diretoria estar disponível no painel.', 'description' => 'Mensagem quando chat está offline'],
            ['key' => 'auto_close_timeout', 'value' => '30', 'description' => 'Tempo em minutos para fechar sessão inativa automaticamente'],
            ['key' => 'max_concurrent_sessions', 'value' => '10', 'description' => 'Número máximo de sessões simultâneas por atendente'],
            ['key' => 'notification_sound', 'value' => 'true', 'description' => 'Tocar som de notificação'],
        ];

        foreach ($configs as $config) {
            ChatConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}

