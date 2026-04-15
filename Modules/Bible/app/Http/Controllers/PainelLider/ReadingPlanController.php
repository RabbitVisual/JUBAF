<?php

namespace Modules\Bible\App\Http\Controllers\PainelLider;

use Modules\Bible\App\Http\Controllers\PainelJovens\ReadingPlanController as BaseReadingPlanController;

class ReadingPlanController extends BaseReadingPlanController
{
    protected string $plansViewsNamespace = 'bible::painellider.plans';

    protected string $bibleRoutesPrefix = 'lideres.bible';
}
