<?php

namespace Modules\Calendario\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Modules\Calendario\App\Models\CalendarEvent;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventExportController extends Controller
{
    public function csvRegistrations(CalendarEvent $event): StreamedResponse
    {
        $this->authorize('manageRegistrations', $event);

        $rows = $event->registrations()->with('user')->orderBy('id')->get();

        $filename = 'inscricoes-evento-'.$event->id.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($rows, $event): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['event_id', 'event_title', 'registration_id', 'user_id', 'nome', 'email', 'status', 'checkin_token', 'amount_charged', 'checked_in_at']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $event->id,
                    $event->title,
                    $r->id,
                    $r->user_id,
                    $r->user?->name,
                    $r->user?->email,
                    $r->status,
                    $r->checkin_token,
                    $r->amount_charged,
                    $r->checked_in_at?->toIso8601String(),
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function pdfBadges(CalendarEvent $event): Response
    {
        $this->authorize('manageRegistrations', $event);

        $regs = $event->registrations()
            ->with('user')
            ->where('status', '!=', \Modules\Calendario\App\Models\CalendarRegistration::STATUS_CANCELLED)
            ->orderBy('id')
            ->get();

        $logoPath = public_path('images/logo/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode((string) file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('calendario::paineldiretoria.exports.badges_pdf', [
            'event' => $event,
            'registrations' => $regs,
            'logoDataUri' => $logoData ? 'data:image/png;base64,'.$logoData : null,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('credenciais-'.$event->id.'.pdf');
    }
}
