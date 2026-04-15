<?php

namespace Modules\Chat\App\Services;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class ChatWhatsAppIngressService
{
    public function __construct(
        private ErpConversationService $erpConversationService
    ) {}

    /**
     * Extrai texto e remetente (JID) de payloads comuns da Evolution API.
     *
     * @return array{from: string, body: string}|null
     */
    public function parseInbound(Request $request): ?array
    {
        $data = $request->all();

        $from = data_get($data, 'data.key.remoteJid')
            ?? data_get($data, 'sender')
            ?? data_get($data, 'from');

        $body = data_get($data, 'data.message.conversation')
            ?? data_get($data, 'data.message.extendedTextMessage.text')
            ?? data_get($data, 'text')
            ?? data_get($data, 'message');

        if ($from === null || $body === null || $body === '') {
            return null;
        }

        if (! is_string($from)) {
            return null;
        }

        $body = is_string($body) ? $body : (string) json_encode($body);

        return ['from' => $from, 'body' => $body];
    }

    public function handleInbound(string $remoteJid, string $body): void
    {
        $digits = $this->normalizeDigitsFromJid($remoteJid);
        if ($digits === '') {
            Log::warning('WhatsApp ingress: JID sem dígitos.', ['jid' => $remoteJid]);

            return;
        }

        $user = $this->findUserByPhoneDigits($digits);
        if (! $user) {
            Log::info('WhatsApp ingress: utilizador não encontrado.', ['digits' => $digits]);

            return;
        }

        $agent = $this->pickInboundAgent();
        if (! $agent) {
            Log::warning('WhatsApp ingress: sem agente diretoria disponível.');

            return;
        }

        $conversation = $this->erpConversationService->findOrCreateDirectDm($user, $agent);

        if ($conversation->whatsapp_remote_jid === null) {
            $conversation->forceFill(['whatsapp_remote_jid' => $remoteJid])->saveQuietly();
        }

        $this->erpConversationService->appendMessage($conversation, $user, $body);
    }

    private function normalizeDigitsFromJid(string $jid): string
    {
        $jid = preg_replace('/@.*$/', '', $jid) ?? $jid;
        $digits = preg_replace('/\D+/', '', $jid) ?? '';

        return $this->normalizePhone($digits);
    }

    private function findUserByPhoneDigits(string $digits): ?User
    {
        foreach (User::query()->where('active', true)->whereNotNull('phone')->cursor() as $user) {
            if ($this->normalizePhone((string) $user->phone) === $digits) {
                return $user;
            }
        }

        return null;
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return '';
        }
        if (! str_starts_with($digits, '244') && strlen($digits) <= 9) {
            $digits = '244'.$digits;
        }

        return $digits;
    }

    private function pickInboundAgent(): ?User
    {
        $names = JubafRoleRegistry::directorateRoleNames();

        return User::query()
            ->where('active', true)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $names))
            ->orderBy('id')
            ->first();
    }
}
