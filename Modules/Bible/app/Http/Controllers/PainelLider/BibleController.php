<?php

namespace Modules\Bible\App\Http\Controllers\PainelLider;

use Modules\Bible\App\Http\Controllers\PainelJovens\BibleController as BaseBibleController;

class BibleController extends BaseBibleController
{
    protected string $bibleViewsNamespace = 'bible::painellider.bible';

    protected string $bibleRoutesPrefix = 'lideres.bible';
}
