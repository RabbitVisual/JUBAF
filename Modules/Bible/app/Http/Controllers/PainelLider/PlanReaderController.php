<?php

namespace Modules\Bible\App\Http\Controllers\PainelLider;

use Modules\Bible\App\Http\Controllers\PainelJovens\PlanReaderController as BasePlanReaderController;

class PlanReaderController extends BasePlanReaderController
{
    protected string $plansViewsNamespace = 'bible::painellider.plans';

    protected string $bibleRoutesPrefix = 'lideres.bible';
}
