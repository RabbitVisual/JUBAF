<?php

namespace Modules\Secretaria\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Secretaria\App\Http\Controllers\Concerns\RendersSecretariaPanelViews;
use Illuminate\Support\Facades\Storage;
use Modules\Igrejas\App\Models\Church;
use Modules\Secretaria\App\Models\SecretariaDocument;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    use RendersSecretariaPanelViews;

    protected function routePrefix(): string
    {
        return 'diretoria.secretaria.arquivo';
    }

    protected function panelLayout(): string
    {
        return 'paineldiretoria::components.layouts.app';
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', SecretariaDocument::class);
        $q = SecretariaDocument::query()->with(['church', 'uploadedBy'])->orderByDesc('created_at');
        if ($request->filled('visibility')) {
            $q->where('visibility', $request->string('visibility'));
        }
        $documents = $q->paginate(20)->withQueryString();

        return $this->secretariaView('documents.index', [
            'documents' => $documents,
            'filters' => $request->only(['visibility']),
        ]);
    }

    public function create()
    {
        $this->authorize('create', SecretariaDocument::class);

        return $this->secretariaView('documents.create', [
            'churches' => Church::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', SecretariaDocument::class);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'visibility' => ['required', 'string', 'in:directorate,leaders,public'],
            'church_id' => ['nullable', 'exists:igrejas_churches,id'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('secretaria/documents', 'local');

        SecretariaDocument::create([
            'title' => $data['title'],
            'visibility' => $data['visibility'],
            'church_id' => $data['church_id'] ?? null,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by_id' => $request->user()->id,
        ]);

        return redirect()->route($this->routePrefix().'.index')->with('success', 'Documento carregado.');
    }

    public function destroy(SecretariaDocument $document)
    {
        $this->authorize('delete', $document);
        Storage::disk('local')->delete($document->path);
        $document->delete();

        return redirect()->route($this->routePrefix().'.index')->with('success', 'Documento removido.');
    }

    public function download(SecretariaDocument $document): StreamedResponse
    {
        $this->authorize('download', $document);
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path, $document->original_name ?? 'documento');
    }
}
