<?php

namespace Modules\Notificacoes\App\Contracts;

use App\Models\User;

interface WhatsAppMessageSender
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function send(User $user, string $template, array $data = []): void;
}
