<?php

namespace Modules\Diretoria\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Diretoria\Http\Requests\StoreBoardMinuteRequest;
use Modules\Diretoria\Http\Requests\UpdateBoardMinuteRequest;
use Modules\Diretoria\Models\BoardMinute;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BoardMinuteMemberController extends Controller
{
    protected function authorizeGovernanceView(): void
    {
        abort_unless(
            auth()->user()?->canAccessAny(['governance_manage', 'governance_view']),
            403,
            'Sem permissão para ver documentos de governança.'
        );
    }

    protected function authorizeGovernanceManage(): void
    {
        abort_unless(
            auth()->user()?->canAccess('governance_manage'),
            403,
            'Sem permissão para gerir atas da diretoria.'
        );
    }

    public function index(Request $request): View
    {
        $this->authorizeGovernanceView();

        $tag = $request->query('tag');
        $query = BoardMinute::query()->orderByDesc('meeting_date')->orderByDesc('id');

        if ($tag && array_key_exists($tag, BoardMinute::tagLabels())) {
            $query->where('tag', $tag);
        }

        $minutes = $query->paginate(12)->withQueryString();

        return view('diretoria::memberpanel.minutes.index', [
            'minutes' => $minutes,
            'tagFilter' => $tag,
            'tagLabels' => BoardMinute::tagLabels(),
        ]);
    }

    public function create(): View
    {
        $this->authorizeGovernanceManage();

        return view('diretoria::memberpanel.minutes.create', [
            'tagLabels' => BoardMinute::tagLabels(),
        ]);
    }

    public function store(StoreBoardMinuteRequest $request): RedirectResponse
    {
        $path = $request->file('pdf')->store('diretoria/board-minutes', 'public');

        BoardMinute::query()->create([
            'title' => $request->validated('title'),
            'meeting_date' => $request->validated('meeting_date'),
            'tag' => $request->validated('tag'),
            'pdf_path' => $path,
            'notes' => $request->validated('notes'),
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('memberpanel.governance.diretoria.minutes.index')
            ->with('success', 'Ata registrada com sucesso.');
    }

    public function edit(BoardMinute $board_minute): View
    {
        $this->authorizeGovernanceManage();

        return view('diretoria::memberpanel.minutes.edit', [
            'minute' => $board_minute,
            'tagLabels' => BoardMinute::tagLabels(),
        ]);
    }

    public function update(UpdateBoardMinuteRequest $request, BoardMinute $board_minute): RedirectResponse
    {
        $data = $request->safe()->except('pdf');

        if ($request->hasFile('pdf')) {
            if ($board_minute->pdf_path && Storage::disk('public')->exists($board_minute->pdf_path)) {
                Storage::disk('public')->delete($board_minute->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf')->store('diretoria/board-minutes', 'public');
        }

        $board_minute->update($data);

        return redirect()
            ->route('memberpanel.governance.diretoria.minutes.index')
            ->with('success', 'Ata atualizada.');
    }

    public function destroy(BoardMinute $board_minute): RedirectResponse
    {
        $this->authorizeGovernanceManage();

        if ($board_minute->pdf_path && Storage::disk('public')->exists($board_minute->pdf_path)) {
            Storage::disk('public')->delete($board_minute->pdf_path);
        }
        $board_minute->delete();

        return redirect()
            ->route('memberpanel.governance.diretoria.minutes.index')
            ->with('success', 'Ata removida.');
    }

    public function download(BoardMinute $board_minute): BinaryFileResponse
    {
        $this->authorizeGovernanceView();

        if (! Storage::disk('public')->exists($board_minute->pdf_path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        $filename = \Illuminate\Support\Str::slug($board_minute->title).'.pdf';

        return response()->download(
            Storage::disk('public')->path($board_minute->pdf_path),
            $filename
        );
    }
}
