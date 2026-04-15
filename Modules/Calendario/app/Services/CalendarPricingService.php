<?php

namespace Modules\Calendario\App\Services;

use Carbon\CarbonInterface;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarEventBatch;
use Modules\Calendario\App\Models\CalendarPriceRule;

class CalendarPricingService
{
    /**
     * @param  array{discount_code?: string|null, batch_id?: int|null, at?: CarbonInterface}  $context
     */
    public function calculateRegistrationTotal(CalendarEvent $event, array $context = []): float
    {
        $at = $context['at'] ?? now();
        $discountCode = isset($context['discount_code']) ? trim((string) $context['discount_code']) : '';
        $batchId = isset($context['batch_id']) ? (int) $context['batch_id'] : null;

        $event->loadMissing(['batches', 'priceRules']);

        $batch = null;
        if ($batchId) {
            $batch = $event->batches->firstWhere('id', $batchId);
            if (! $batch instanceof CalendarEventBatch) {
                $batch = null;
            }
        }

        $base = $this->resolveBasePrice($event, $batch, $at);
        $rules = $this->applicableRules($event, $batch);

        $registrationData = [
            'discount_code' => $discountCode,
            'created_at' => $at,
        ];

        if ($batch !== null) {
            return round($this->applyBatchRules($rules, (float) $base, $registrationData), 2);
        }

        return round($this->applyGlobalRules($rules, (float) $base, $registrationData), 2);
    }

    protected function resolveBasePrice(CalendarEvent $event, ?CalendarEventBatch $batch, CarbonInterface $at): float
    {
        if ($batch !== null) {
            if (! $batch->isSaleOpen($at)) {
                return (float) $event->ticket_price;
            }

            return (float) $batch->price;
        }

        return (float) ($event->ticket_price ?? 0);
    }

    /**
     * @return \Illuminate\Support\Collection<int, CalendarPriceRule>
     */
    protected function applicableRules(CalendarEvent $event, ?CalendarEventBatch $batch)
    {
        return $event->priceRules
            ->filter(fn (CalendarPriceRule $r) => $r->is_active)
            ->filter(function (CalendarPriceRule $r) use ($batch) {
                if ($batch === null) {
                    return $r->event_batch_id === null;
                }

                return $r->event_batch_id === null || (int) $r->event_batch_id === (int) $batch->id;
            })
            ->sortBy(['priority', 'id'])
            ->values();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, CalendarPriceRule>  $rules
     * @param  array{discount_code: string, created_at: CarbonInterface}  $registrationData
     */
    protected function applyBatchRules($rules, float $basePrice, array $registrationData): float
    {
        $price = $basePrice;
        foreach ($rules as $rule) {
            if ($this->ruleMatches($rule, [], $registrationData)) {
                $price = $this->applyRule($rule, $price);

                return max(0.0, $price);
            }
        }

        return max(0.0, $price);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, CalendarPriceRule>  $rules
     */
    protected function applyGlobalRules($rules, float $basePrice, array $registrationData): float
    {
        $price = $basePrice;
        foreach ($rules as $rule) {
            if ($this->ruleMatches($rule, [], $registrationData)) {
                $price = $this->applyRule($rule, $price);
                break;
            }
        }

        return max(0.0, $price);
    }

    /**
     * @param  array<string, mixed>  $participantData
     * @param  array{discount_code: string, created_at: CarbonInterface}  $registrationData
     */
    public function ruleMatches(CalendarPriceRule $rule, array $participantData, array $registrationData): bool
    {
        $cfg = $rule->config ?? [];

        return match ($rule->rule_type) {
            CalendarPriceRule::TYPE_DISCOUNT_CODE => isset($cfg['code'])
                && strcasecmp((string) $cfg['code'], (string) ($registrationData['discount_code'] ?? '')) === 0,
            CalendarPriceRule::TYPE_EARLY_BIRD => isset($cfg['until'])
                && $registrationData['created_at']->lte(\Carbon\Carbon::parse((string) $cfg['until'])),
            CalendarPriceRule::TYPE_PERCENT_OFF => isset($cfg['percent']),
            CalendarPriceRule::TYPE_FIXED_PRICE => isset($cfg['amount']),
            default => false,
        };
    }

    protected function applyRule(CalendarPriceRule $rule, float $price): float
    {
        $cfg = $rule->config ?? [];

        return match ($rule->rule_type) {
            CalendarPriceRule::TYPE_PERCENT_OFF => $price * (1 - ((float) ($cfg['percent'] ?? 0) / 100)),
            CalendarPriceRule::TYPE_FIXED_PRICE => (float) ($cfg['amount'] ?? $price),
            CalendarPriceRule::TYPE_DISCOUNT_CODE => isset($cfg['percent'])
                ? $price * (1 - ((float) $cfg['percent'] / 100))
                : (isset($cfg['amount']) ? max(0, $price - (float) $cfg['amount']) : $price),
            CalendarPriceRule::TYPE_EARLY_BIRD => isset($cfg['percent'])
                ? $price * (1 - ((float) $cfg['percent'] / 100))
                : $price,
            default => $price,
        };
    }
}
