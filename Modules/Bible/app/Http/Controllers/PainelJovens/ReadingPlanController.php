<?php

namespace Modules\Bible\App\Http\Controllers\PainelJovens;

use App\Http\Controllers\Controller;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Bible\App\Models\BiblePlan;
use Modules\Bible\App\Models\BiblePlanSubscription;
use Modules\Bible\App\Services\ReadingCatchUpService;
use Illuminate\Http\Response;

class ReadingPlanController extends Controller
{
    protected string $plansViewsNamespace = 'bible::paineljovens.plans';

    protected string $bibleRoutesPrefix = 'member.bible';

    public function __construct(
        protected PdfService $pdfService,
        protected ReadingCatchUpService $catchUpService
    ) {}

    protected function plansView(string $name, array $data = [])
    {
        return view($this->plansViewsNamespace.'.'.$name, $data);
    }

    protected function bibleRedirect(string $routeSuffix, array $parameters = [], int $status = 302, array $headers = [])
    {
        return redirect()->route($this->bibleRoutesPrefix.'.'.$routeSuffix, $parameters, $status, $headers);
    }

    public function index()
    {
        $user = Auth::user();

        $subscriptions = BiblePlanSubscription::with(['plan', 'progress'])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->filter(fn ($sub) => $sub->plan !== null);

        foreach ($subscriptions as $sub) {
            $total = $sub->plan->days()->count();
            $sub->total_days = $total ?: $sub->plan->duration_days;
            $completed = $sub->progress()->count();
            $sub->percent = $total > 0 ? round(($completed / $total) * 100) : 0;
            $sub->offer_recalculate = $this->catchUpService->shouldOfferRecalculate($sub);
        }

        return $this->plansView('dashboard', compact('subscriptions'));
    }

    public function catalog(Request $request)
    {
        $subscribedPlanIds = BiblePlanSubscription::where('user_id', Auth::id())->pluck('plan_id');

        $featuredPlans = BiblePlan::where('is_active', true)
            ->where('is_featured', true)
            ->whereNotIn('id', $subscribedPlanIds)
            ->limit(3)
            ->get();

        $query = BiblePlan::where('is_active', true)
            ->whereNotIn('id', $subscribedPlanIds);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allPlans = $query->latest()
            ->paginate(12);

        return $this->plansView('catalog', compact('featuredPlans', 'allPlans'));
    }

    public function preview($id)
    {
        if (BiblePlanSubscription::where('user_id', Auth::id())->where('plan_id', $id)->exists()) {
            return $this->bibleRedirect('plans.index');
        }

        $plan = BiblePlan::withCount('days')->findOrFail($id);

        $sampleDays = $plan->days()->with('contents.book')->orderBy('day_number')->take(5)->get();

        return $this->plansView('preview', compact('plan', 'sampleDays'));
    }

    public function subscribe(Request $request, $id)
    {
        $plan = BiblePlan::findOrFail($id);

        if (BiblePlanSubscription::where('user_id', Auth::id())->where('plan_id', $id)->exists()) {
            return $this->bibleRedirect('plans.index')->with('info', 'Você já está inscrito neste plano.');
        }

        BiblePlanSubscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'start_date' => now(),
            'current_day_number' => 1,
        ]);

        return $this->bibleRedirect('plans.index')->with('success', 'Inscrição realizada! Boa leitura.');
    }

    public function show($id)
    {
        $subscription = BiblePlanSubscription::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        return $this->bibleRedirect('reader', ['subscriptionId' => $subscription->id, 'day' => $subscription->current_day_number]);
    }

    public function recalculate(Request $request, $subscriptionId)
    {
        $subscription = BiblePlanSubscription::with('plan')
            ->where('user_id', Auth::id())
            ->where('id', $subscriptionId)
            ->firstOrFail();

        if (! $this->catchUpService->shouldOfferRecalculate($subscription)) {
            return $this->bibleRedirect('plans.index')->with('info', 'Recálculo não disponível para este plano.');
        }

        $this->catchUpService->recalculateRemainingRoute($subscription);

        return $this->bibleRedirect('plans.index')->with('success', 'Rotas recalculadas. A leitura restante foi redistribuída até a data final.');
    }

    public function downloadPdf($id): Response
    {
        $plan = null;
        $subscription = BiblePlanSubscription::where('user_id', Auth::id())->where('id', $id)->first();

        if ($subscription) {
            $plan = $subscription->plan;
        } else {
            $plan = BiblePlan::findOrFail($id);
        }

        $days = $plan->days()->with(['contents' => function ($q) {
            $q->with('book');
        }])->orderBy('day_number')->get();

        return $this->pdfService->downloadView(
            $this->plansViewsNamespace.'.pdf',
            compact('plan', 'days'),
            'plano-'.\Illuminate\Support\Str::slug($plan->title).'.pdf',
            'A4',
            'Portrait',
            [15, 15, 15, 15]
        );
    }
}
