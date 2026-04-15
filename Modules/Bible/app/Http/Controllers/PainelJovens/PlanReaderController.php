<?php

namespace Modules\Bible\App\Http\Controllers\PainelJovens;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Bible\App\Models\BiblePlanDay;
use Modules\Bible\App\Models\BiblePlanSubscription;
use Modules\Bible\App\Models\BibleUserProgress;
use Modules\Bible\App\Models\UserReadingLog;
use Modules\Bible\App\Services\BadgeService;

class PlanReaderController extends Controller
{
    protected string $plansViewsNamespace = 'bible::paineljovens.plans';

    protected string $bibleRoutesPrefix = 'member.bible';

    protected function plansView(string $name, array $data = [])
    {
        return view($this->plansViewsNamespace.'.'.$name, $data);
    }

    protected function bibleRedirect(string $routeSuffix, array $parameters = [], int $status = 302, array $headers = [])
    {
        return redirect()->route($this->bibleRoutesPrefix.'.'.$routeSuffix, $parameters, $status, $headers);
    }

    public function read($subscriptionId, $dayNumber)
    {
        $subscription = BiblePlanSubscription::with('plan')
            ->where('user_id', Auth::id())
            ->where('id', $subscriptionId)
            ->firstOrFail();

        $day = BiblePlanDay::with(['contents.book'])
            ->where('plan_id', $subscription->plan_id)
            ->where('day_number', $dayNumber)
            ->firstOrFail();

        $versionAbbr = request('version');
        if ($versionAbbr) {
            $targetVersion = \Modules\Bible\App\Models\BibleVersion::whereRaw('LOWER(abbreviation) = ?', [strtolower($versionAbbr)])->first();
        }

        if (! isset($targetVersion) || ! $targetVersion) {
            $globalAbbr = \App\Models\Settings::get('default_bible_version_abbreviation', '');
            if ($globalAbbr !== '') {
                $targetVersion = \Modules\Bible\App\Models\BibleVersion::where('abbreviation', $globalAbbr)
                    ->where('is_active', true)->first();
            }
            if (! isset($targetVersion) || ! $targetVersion) {
                $targetVersion = \Modules\Bible\App\Models\BibleVersion::where('is_default', true)->first()
                    ?? \Modules\Bible\App\Models\BibleVersion::where('is_active', true)->first();
            }
        }

        foreach ($day->contents as $content) {
            if ($content->type === 'scripture') {
                $actualTargetBookId = null;
                $lookupBookNumber = null;
                $lookupBookName = null;

                if ($content->book) {
                    $lookupBookNumber = $content->book->book_number;
                    $lookupBookName = $content->book->name;
                } else {
                    $originalBookFallback = \Modules\Bible\App\Models\Book::find($content->book_id);
                    if ($originalBookFallback) {
                        $lookupBookNumber = $originalBookFallback->book_number;
                        $lookupBookName = $originalBookFallback->name;
                    }
                }

                if ($targetVersion && $lookupBookNumber) {
                    $smartBook = \Modules\Bible\App\Models\Book::where('bible_version_id', $targetVersion->id)
                        ->where('book_number', $lookupBookNumber)
                        ->first();

                    if ($smartBook) {
                        $actualTargetBookId = $smartBook->id;
                        $content->target_book_name = $smartBook->name;
                    } elseif ($lookupBookName) {
                        $smartBookByName = \Modules\Bible\App\Models\Book::where('bible_version_id', $targetVersion->id)
                            ->where('name', $lookupBookName)
                            ->first();
                        if ($smartBookByName) {
                            $actualTargetBookId = $smartBookByName->id;
                            $content->target_book_name = $smartBookByName->name;
                            $content->target_book_id = $smartBookByName->id;
                        }
                    }
                }

                if ($actualTargetBookId) {
                    $chapterStart = (int) $content->chapter_start;
                    $chapterEnd = (int) ($content->chapter_end ?: $content->chapter_start);
                    $verseStart = $content->verse_start;
                    $verseEnd = $content->verse_end;

                    $query = \Modules\Bible\App\Models\Verse::select('verses.*')
                        ->join('chapters', 'verses.chapter_id', '=', 'chapters.id')
                        ->where('chapters.book_id', $actualTargetBookId)
                        ->orderBy('chapters.chapter_number')
                        ->orderBy('verses.verse_number')
                        ->with('chapter');

                    if ($chapterStart == $chapterEnd) {
                        $query->where('chapters.chapter_number', $chapterStart);
                        if ($verseStart) {
                            $query->where('verses.verse_number', '>=', $verseStart);
                        }
                        if ($verseEnd) {
                            $query->where('verses.verse_number', '<=', $verseEnd);
                        }
                    } else {
                        $query->where(function ($q) use ($chapterStart, $chapterEnd, $verseStart, $verseEnd) {
                            $q->where(function ($sub) use ($chapterStart, $verseStart) {
                                $sub->where('chapters.chapter_number', $chapterStart);
                                if ($verseStart) {
                                    $sub->where('verses.verse_number', '>=', $verseStart);
                                }
                            });

                            if ($chapterEnd > $chapterStart + 1) {
                                $q->orWhereBetween('chapters.chapter_number', [$chapterStart + 1, $chapterEnd - 1]);
                            }

                            $q->orWhere(function ($sub) use ($chapterEnd, $verseEnd) {
                                $sub->where('chapters.chapter_number', $chapterEnd);
                                if ($verseEnd) {
                                    $sub->where('verses.verse_number', '<=', $verseEnd);
                                }
                            });
                        });
                    }

                    $content->verses = $query->get()->groupBy(fn ($verse) => $verse->chapter->chapter_number);
                } else {
                    $content->verses = collect();
                }
            }
        }

        $isCompleted = BibleUserProgress::where('subscription_id', $subscription->id)
            ->where('plan_day_id', $day->id)
            ->exists();

        $isLocked = false;
        if ($isCompleted && ! $subscription->plan->allow_back_tracking) {
            $isLocked = true;
        }

        $prevDay = $dayNumber > 1 ? $dayNumber - 1 : null;
        $totalDays = $subscription->plan->days()->count();
        $nextDay = $dayNumber < $totalDays ? $dayNumber + 1 : null;

        $userNote = \Modules\Bible\App\Models\BibleUserNote::where('user_id', Auth::id())
            ->where('plan_day_id', $day->id)
            ->first();

        $verseIds = [];
        foreach ($day->contents as $content) {
            if ($content->type === 'scripture' && $content->verses) {
                $verseIds = array_merge($verseIds, $content->verses->flatten()->pluck('id')->toArray());
            }
        }

        $userFavorites = [];
        if (! empty($verseIds)) {
            $userFavorites = \Modules\Bible\App\Models\BibleFavorite::where('user_id', Auth::id())
                ->whereIn('verse_id', $verseIds)
                ->get()
                ->keyBy('verse_id');
        }

        $versions = \Modules\Bible\App\Models\BibleVersion::where('is_active', true)->orderBy('name')->get();

        return $this->plansView('reader', compact('subscription', 'day', 'isCompleted', 'isLocked', 'prevDay', 'nextDay', 'userNote', 'versions', 'targetVersion', 'userFavorites'));
    }

    public function complete(Request $request, $subscriptionId, $dayId)
    {
        $subscription = BiblePlanSubscription::where('user_id', Auth::id())->findOrFail($subscriptionId);
        $day = BiblePlanDay::where('plan_id', $subscription->plan_id)->findOrFail($dayId);

        BibleUserProgress::firstOrCreate(
            [
                'subscription_id' => $subscription->id,
                'plan_day_id' => $day->id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        UserReadingLog::firstOrCreate(
            [
                'subscription_id' => $subscription->id,
                'plan_day_id' => $day->id,
            ],
            [
                'user_id' => Auth::id(),
                'day_number' => $day->day_number,
                'completed_at' => now(),
            ]
        );

        app(BadgeService::class)->evaluateAfterCompletion($subscription, $day);

        if ($subscription->current_day_number == $day->day_number) {
            $nextDay = $day->day_number + 1;
            if ($nextDay <= $subscription->plan->days()->count()) {
                $subscription->update(['current_day_number' => $nextDay]);
            } else {
                $subscription->update(['is_completed' => true]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'completed' => true]);
        }

        return $this->bibleRedirect('reader.congratulations', [$subscription->id, $day->id]);
    }

    public function uncomplete(Request $request, $subscriptionId, $dayId)
    {
        $subscription = BiblePlanSubscription::where('user_id', Auth::id())->findOrFail($subscriptionId);
        $day = BiblePlanDay::where('plan_id', $subscription->plan_id)->findOrFail($dayId);

        BibleUserProgress::where('subscription_id', $subscription->id)
            ->where('plan_day_id', $day->id)
            ->delete();

        UserReadingLog::where('subscription_id', $subscription->id)
            ->where('plan_day_id', $day->id)
            ->delete();

        if ($subscription->current_day_number > $day->day_number) {
            $subscription->update(['current_day_number' => $day->day_number]);
        }
        if ($subscription->is_completed) {
            $subscription->update(['is_completed' => false]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'completed' => false]);
        }

        return back()->with('success', 'Leitura desmarcada.');
    }

    public function congratulations($subscriptionId, $dayId)
    {
        $subscription = BiblePlanSubscription::with('plan')->where('user_id', Auth::id())->findOrFail($subscriptionId);
        $day = BiblePlanDay::where('plan_id', $subscription->plan_id)->findOrFail($dayId);

        $nextDayNum = $day->day_number + 1;
        $totalDays = $subscription->plan->days()->count();
        $nextDay = ($nextDayNum <= $totalDays) ? $nextDayNum : null;

        return $this->plansView('congratulations', compact('subscription', 'day', 'nextDay'));
    }

    public function storeNote(Request $request, $subscriptionId, $dayId)
    {
        $request->validate([
            'note_content' => 'required|string',
        ]);

        \Modules\Bible\App\Models\BibleUserNote::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'plan_day_id' => $dayId,
            ],
            [
                'note_content' => $request->note_content,
                'color_code' => $request->input('color_code', '#ffee00'),
            ]
        );

        return back()->with('success', 'Anotação salva.');
    }
}
