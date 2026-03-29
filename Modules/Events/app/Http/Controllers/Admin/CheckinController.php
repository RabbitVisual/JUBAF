<?php

namespace Modules\Events\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Events\App\Models\Event;
use Modules\Events\App\Models\EventRegistration;

class CheckinController extends Controller
{
    public function index()
    {
        Gate::authorize('checkin', Event::class);

        return view('events::admin.checkin.scanner');
    }

    public function scannerFullscreen()
    {
        Gate::authorize('checkin', Event::class);

        return view('events::admin.checkin.scanner-fullscreen');
    }

    public function validateCheckin(Request $request)
    {
        Gate::authorize('checkin', Event::class);
        $request->validate([
            'ticket_hash' => 'required|string',
        ]);

        $hash = $request->ticket_hash;

        $registration = EventRegistration::where('ticket_hash', $hash)->with('latestPayment')->first();

        if (! $registration) {
            return response()->json([
                'success' => false,
                'message' => 'Ingresso não encontrado.',
            ]);
        }

        // Check status
        if ($registration->status !== EventRegistration::STATUS_CONFIRMED) {
            return response()->json([
                'success' => false,
                'message' => 'Ingresso não confirmado (Status: '.$registration->status.').',
            ]);
        }

        // Pagamento online: se existir registo de Payment, o gateway tem de estar em "completed".
        // Inscrições gratuitas ou só com confirmação manual costumam não ter Payment — nesse caso mantemos o fluxo só com status confirmed.
        $payment = $registration->latestPayment;
        if ($payment !== null && $payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Pagamento ainda não confirmado no gateway (estado: '.$payment->status.'). Conclua o pagamento antes do check-in.',
            ]);
        }

        // Check if already checked in
        if ($registration->checked_in_at) {
            return response()->json([
                'success' => false,
                'message' => 'ATENÇÃO: Este ingresso JÁ FOI UTILIZADO em '.$registration->checked_in_at->format('d/m H:i').'.',
            ]);
        }

        // Perform Check-in
        $registration->update(['checked_in_at' => now()]);

        return response()->json([
            'success' => true,
            'user_name' => $registration->user->name ?? 'Visitante',
            'ticket_type' => $registration->batch?->name ?? 'Geral',
            'headline' => 'Check-in confirmado',
            'payment_note' => $payment !== null
                ? 'Pagamento confirmado no gateway.'
                : 'Inscrição confirmada (sem pagamento online obrigatório).',
        ]);
    }
}
