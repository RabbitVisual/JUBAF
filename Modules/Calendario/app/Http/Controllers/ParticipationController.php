<?php

namespace Modules\Calendario\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;
use Modules\Calendario\App\Events\InscricaoConfirmada;
use Modules\Calendario\App\Services\CalendarPricingService;
use Modules\Calendario\App\Services\EventService;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Services\PaymentOrchestrator;

class ParticipationController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('calendario.participate'), 403);

        $user = $request->user();
        $q = CalendarEvent::query()
            ->where('status', CalendarEvent::STATUS_PUBLISHED)
            ->where('start_date', '>=', now()->subDay())
            ->orderBy('start_date');

        $events = $q->get()->filter(function (CalendarEvent $e) use ($user) {
            if (! $e->userCanView($user)) {
                return false;
            }

            return $e->churchScopeAllows($user);
        });

        if ($request->routeIs('jovens.*')) {
            return view('calendario::paineljovens.index', [
                'events' => $events,
                'routePrefix' => 'jovens.calendario',
                'hasPublicCalendar' => Route::has('eventos.index'),
            ]);
        }

        return view('calendario::painellider.index', [
            'events' => $events,
            'routePrefix' => 'lideres.calendario',
            'hasPublicCalendar' => Route::has('eventos.index'),
        ]);
    }

    public function show(Request $request, CalendarEvent $event): View
    {
        abort_unless($request->user()?->can('calendario.participate'), 403);
        $user = $request->user();
        abort_unless($event->status === CalendarEvent::STATUS_PUBLISHED, 404);
        abort_unless($event->userCanView($user) && $event->churchScopeAllows($user), 403);

        $event->load(['batches', 'priceRules']);

        $registration = CalendarRegistration::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($request->routeIs('jovens.*')) {
            return view('calendario::paineljovens.show', [
                'event' => $event,
                'registration' => $registration,
                'routePrefix' => 'jovens.calendario',
                'hasPublicCalendar' => Route::has('eventos.index'),
            ]);
        }

        return view('calendario::painellider.show', [
            'event' => $event,
            'registration' => $registration,
            'routePrefix' => 'lideres.calendario',
            'hasPublicCalendar' => Route::has('eventos.index'),
        ]);
    }

    public function register(Request $request, CalendarEvent $event): RedirectResponse
    {
        abort_unless($request->user()?->can('calendario.participate'), 403);
        $user = $request->user();
        abort_unless($event->status === CalendarEvent::STATUS_PUBLISHED, 403);
        abort_unless($event->userCanView($user) && $event->churchScopeAllows($user), 403);
        abort_unless($event->registration_open, 403);
        abort_unless($event->isRegistrationPeriodOpen(), 403);

        $request->validate([
            'discount_code' => ['nullable', 'string', 'max:64'],
            'event_batch_id' => ['nullable', 'integer'],
        ]);

        $event->load(['batches', 'priceRules']);

        $batchId = $request->filled('event_batch_id') ? (int) $request->input('event_batch_id') : null;
        if ($batchId) {
            $belongs = $event->batches->contains('id', $batchId);
            abort_unless($belongs, 422);
        }

        $pricing = app(CalendarPricingService::class);
        $fee = $pricing->calculateRegistrationTotal($event, [
            'discount_code' => $request->input('discount_code'),
            'batch_id' => $batchId,
        ]);

        $existing = CalendarRegistration::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing && $existing->status !== CalendarRegistration::STATUS_CANCELLED) {
            if ($existing->status === CalendarRegistration::STATUS_PENDING_PAYMENT && $existing->payment_id) {
                $gp = $existing->gatewayPayment;
                if ($gp) {
                    return redirect()
                        ->route('gateway.public.checkout', ['uuid' => $gp->uuid])
                        ->with('warning', 'Completa o pagamento da inscrição.');
                }
            }

            return back()->with('warning', 'Já está inscrito neste evento.');
        }

        if ($fee > 0 && ! module_enabled('Gateway')) {
            return back()->with('error', 'O módulo de pagamentos não está activo. Contacta a tesouraria.');
        }

        if ($fee > 0) {
            $orchestrator = app(PaymentOrchestrator::class);
            $account = $orchestrator->resolveDefaultAccount();
            if (! $account) {
                return back()->with('error', 'A tesouraria ainda não configurou um meio de pagamento online para este valor.');
            }
            $driverKey = $account->driver;

            if ($existing && $existing->status === CalendarRegistration::STATUS_CANCELLED) {
                $existing->update([
                    'status' => CalendarRegistration::STATUS_PENDING_PAYMENT,
                    'payment_status' => 'pending',
                    'event_batch_id' => $batchId,
                    'discount_code' => $request->input('discount_code'),
                    'amount_charged' => null,
                ]);
                $reg = $existing->fresh();
            } else {
                $reg = CalendarRegistration::query()->create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'status' => CalendarRegistration::STATUS_PENDING_PAYMENT,
                    'payment_status' => 'pending',
                    'event_batch_id' => $batchId,
                    'discount_code' => $request->input('discount_code'),
                ]);
            }

            $payment = GatewayPayment::query()->create([
                'gateway_provider_account_id' => $account?->id,
                'driver' => $driverKey,
                'amount' => $fee,
                'currency' => 'BRL',
                'status' => GatewayPayment::STATUS_PENDING,
                'payable_type' => $reg->getMorphClass(),
                'payable_id' => $reg->id,
                'user_id' => $user->id,
                'description' => 'Inscrição: '.$event->title,
            ]);

            $reg->update([
                'payment_id' => $payment->id,
                'payment_status' => 'pending',
                'amount_charged' => $fee,
            ]);

            try {
                $orchestrator->initiatePayment($payment);
            } catch (\Throwable $e) {
                return back()->with('error', 'Não foi possível iniciar o pagamento: '.$e->getMessage());
            }

            return redirect()
                ->route('gateway.public.checkout', ['uuid' => $payment->uuid])
                ->with('success', 'Redirecionamento para pagamento da inscrição.');
        }

        $eventService = app(EventService::class);
        $status = $eventService->resolveRegistrationStatus($event);

        $common = [
            'event_batch_id' => $batchId,
            'discount_code' => $request->input('discount_code'),
            'amount_charged' => 0,
        ];

        if ($existing && $existing->status === CalendarRegistration::STATUS_CANCELLED) {
            $existing->update(array_merge([
                'status' => $status,
                'payment_status' => 'not_required',
            ], $common));
            $confirmedRegistration = $existing->fresh();
        } else {
            $confirmedRegistration = CalendarRegistration::query()->create(array_merge([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => $status,
                'payment_status' => 'not_required',
            ], $common));
        }

        if ($status === CalendarRegistration::STATUS_CONFIRMED) {
            event(new InscricaoConfirmada($confirmedRegistration, new GatewayPayment([
                'uuid' => 'free-'.$confirmedRegistration->id,
                'amount' => 0,
                'status' => GatewayPayment::STATUS_PAID,
            ])));
        }

        return back()->with('success', $status === CalendarRegistration::STATUS_WAITLIST
            ? 'Inscrição em lista de espera.'
            : 'Inscrição confirmada.');
    }

    public function cancel(Request $request, CalendarEvent $event): RedirectResponse
    {
        abort_unless($request->user()?->can('calendario.participate'), 403);
        $user = $request->user();

        $reg = CalendarRegistration::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->authorize('delete', $reg);
        $reg->update(['status' => CalendarRegistration::STATUS_CANCELLED]);

        return back()->with('success', 'Inscrição cancelada.');
    }
}
