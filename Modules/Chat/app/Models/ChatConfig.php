<?php

namespace Modules\Chat\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatConfig extends Model
{
    use HasFactory;

    protected $table = 'chat_configs';

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Obter valor de configuração
     */
    public static function get($key, $default = null)
    {
        $config = self::where('key', $key)->first();
        return $config ? $config->value : $default;
    }

    /**
     * Definir valor de configuração
     */
    public static function set($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }

    /**
     * Verificar se chat está habilitado
     */
    public static function isEnabled()
    {
        return self::get('chat_enabled', 'true') === 'true';
    }

    /**
     * Verificar se chat público está habilitado
     */
    public static function isPublicEnabled()
    {
        return self::get('public_chat_enabled', 'true') === 'true';
    }

    /**
     * Obter horários de funcionamento
     */
    public static function getOpeningHours()
    {
        $hours = self::get('opening_hours', '{}');
        return json_decode($hours, true);
    }

    /**
     * Modo de disponibilidade do chat público:
     * - office_hours: apenas horário configurado
     * - agents_online: apenas quando há atendente com painel aberto (heartbeat)
     * - either: horário OU atendente online
     */
    public static function publicAvailabilityMode(): string
    {
        $mode = self::get('public_availability_mode', 'either');

        return in_array($mode, ['office_hours', 'agents_online', 'either'], true) ? $mode : 'either';
    }

    /**
     * Verificar se o horário de funcionamento permite atendimento agora
     */
    public static function isWithinOpeningHours(): bool
    {
        $hours = self::getOpeningHours();
        $day = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i');

        if (! isset($hours[$day]) || empty($hours[$day]['enabled'])) {
            return false;
        }

        return $currentTime >= $hours[$day]['start'] && $currentTime <= $hours[$day]['end'];
    }

    /**
     * Verificar se chat público está disponível agora
     */
    public static function isAvailableNow(): bool
    {
        if (! self::isEnabled() || ! self::isPublicEnabled()) {
            return false;
        }

        $mode = self::publicAvailabilityMode();
        $hoursOpen = self::isWithinOpeningHours();
        $agentsOnline = \Modules\Chat\App\Services\ChatAgentPresence::countOnline() > 0;

        return match ($mode) {
            'agents_online' => $agentsOnline,
            'either' => $hoursOpen || $agentsOnline,
            default => $hoursOpen,
        };
    }

    /**
     * Obter próxima disponibilidade
     */
    public static function getNextAvailability()
    {
        $hours = self::getOpeningHours();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i');
        $currentDayIndex = array_search($currentDay, $days);

        // Verificar se ainda está disponível hoje
        if (isset($hours[$currentDay]) && $hours[$currentDay]['enabled']) {
            if ($currentTime < $hours[$currentDay]['end']) {
                return [
                    'day' => now()->format('d/m/Y'),
                    'time' => $hours[$currentDay]['start'],
                    'is_today' => true,
                ];
            }
        }

        // Procurar próximo dia disponível
        for ($i = 1; $i <= 7; $i++) {
            $nextDayIndex = ($currentDayIndex + $i) % 7;
            $nextDay = $days[$nextDayIndex];
            
            if (isset($hours[$nextDay]) && $hours[$nextDay]['enabled']) {
                $date = now()->addDays($i);
                return [
                    'day' => $date->format('d/m/Y'),
                    'time' => $hours[$nextDay]['start'],
                    'is_today' => false,
                ];
            }
        }

        return null;
    }
}

