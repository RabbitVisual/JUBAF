<?php

namespace Modules\Talentos\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Talentos\App\Http\Requests\RespondTalentAssignmentRequest;
use Modules\Talentos\App\Models\TalentAssignment;

class TalentAssignmentRespondController extends Controller
{
    public function __invoke(RespondTalentAssignmentRequest $request, TalentAssignment $assignment): RedirectResponse
    {
        $assignment->update(['status' => $request->validated('status')]);

        $message = $assignment->status === TalentAssignment::STATUS_CONFIRMED
            ? 'Confirmámos a sua disponibilidade para esta função. A diretoria será informada.'
            : 'Indicámos que não pode aceitar este convite. A diretoria será informada.';

        $prefix = str_contains($request->route()->getName() ?? '', 'jovens.') ? 'jovens.talentos' : 'lideres.talentos';

        return redirect()
            ->route($prefix.'.profile.edit')
            ->with('success', $message);
    }
}
