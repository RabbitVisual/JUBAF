<?php

namespace Modules\Diretoria\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Modules\Diretoria\Http\Requests\StoreBoardMinuteRequest;
use Modules\Diretoria\Http\Requests\UpdateBoardMinuteRequest;
use Modules\Diretoria\Models\BoardMinute;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BoardMinuteController extends Controller
{
    public function index(Request $request): View
    {
        $tag = $request->query('tag');
        $query = BoardMinute::query()->orderByDesc('meeting_date')->orderByDesc('id');

        if ($tag && array_key_exists($tag, BoardMinute::tagLabels())) {
            $query->where('tag', $tag);
        }

        $minutes = $query->paginate(12)->withQueryString();

        return view('diretoria::admin.minutes.index', [
            'minutes' => $minutes,
            'tagFilter' => $tag,
            'tagLabels' => BoardMinute::tagLabels(),
        ]);
    }

    public function create(): View
    {
        return view('diretoria::admin.minutes.create', [
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
            ->route('admin.diretoria.minutes.index')
            ->with('success', 'Ata registrada com sucesso.');
    }

    public function edit(BoardMinute $board_minute): View
    {
        return view('diretoria::admin.minutes.edit', [
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
            ->route('admin.diretoria.minutes.index')
            ->with('success', 'Ata atualizada.');
    }

    public function destroy(BoardMinute $board_minute): RedirectResponse
    {
        if ($board_minute->pdf_path && Storage::disk('public')->exists($board_minute->pdf_path)) {
            Storage::disk('public')->delete($board_minute->pdf_path);
        }
        $board_minute->delete();

        return redirect()
            ->route('admin.diretoria.minutes.index')
            ->with('success', 'Ata removida.');
    }

    public function download(BoardMinute $board_minute): BinaryFileResponse
    {
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
