<?php

namespace Modules\Notificacoes\App\Mail;

use Modules\Notificacoes\App\Models\Notificacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacaoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Notificacao $notificacao;

    /**
     * Create a new message instance.
     */
    public function __construct(Notificacao $notificacao)
    {
        $this->notificacao = $notificacao;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = "[{$this->notificacao->type_texto}] {$this->notificacao->title}";

        return $this->subject($subject)
            ->view('notificacoes::emails.notificacao')
            ->with([
                'notificacao' => $this->notificacao,
            ]);
    }
}

