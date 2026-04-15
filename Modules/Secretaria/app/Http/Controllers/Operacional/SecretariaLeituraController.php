<?php

namespace Modules\Secretaria\App\Http\Controllers\Operacional;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Secretaria\App\Models\Convocation;
use Modules\Secretaria\App\Models\Minute;
use Modules\Secretaria\App\Models\MinuteAttachment;
use Modules\Secretaria\App\Models\SecretariaDocument;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecretariaLeituraController extends Controller
{
    protected function layout(): string
    {
        return request()->routeIs('lideres.*')
            ? 'painellider::components.layouts.app'
            : 'paineljovens::components.layouts.app';
    }

    protected function homeRoute(): string
    {
        return request()->routeIs('lideres.*') ? 'lideres.dashboard' : 'jovens.dashboard';
    }

    protected function namePrefix(): string
    {
        return request()->routeIs('lideres.*') ? 'lideres.secretaria' : 'jovens.secretaria';
    }

    public function index()
    {
        $this->authorize('viewAny', Minute::class);

        $minutesQuery = Minute::query()->where('status', 'published')->orderByDesc('published_at');
        if (user_needs_secretaria_church_scope()) {
            $minutesQuery->publishedForOperationalChurches(auth()->user());
        }
        $minutes = $minutesQuery->limit(5)->get();
        $convocations = Convocation::query()->where('status', 'published')->where('assembly_at', '>=', now()->startOfDay())->orderBy('assembly_at')->limit(5)->get();

        return view('secretaria::painel-operacional.index', [
            'layout' => $this->layout(),
            'homeRoute' => $this->homeRoute(),
            'namePrefix' => $this->namePrefix(),
            'minutes' => $minutes,
            'convocations' => $convocations,
        ]);
    }

    public function minutes()
    {
        $this->authorize('viewAny', Minute::class);
        $minutesQuery = Minute::query()->where('status', 'published')->orderByDesc('published_at');
        if (user_needs_secretaria_church_scope()) {
            $minutesQuery->publishedForOperationalChurches(auth()->user());
        }
        $minutes = $minutesQuery->paginate(15);

        return view('secretaria::painel-operacional.minutes-index', [
            'layout' => $this->layout(),
            'homeRoute' => $this->homeRoute(),
            'namePrefix' => $this->namePrefix(),
            'minutes' => $minutes,
        ]);
    }

    public function minuteShow(Minute $minute)
    {
        $this->authorize('view', $minute);
        $minute->load(['meeting', 'church', 'attachments']);

        return view('secretaria::painel-operacional.minute-show', [
            'layout' => $this->layout(),
            'homeRoute' => $this->homeRoute(),
            'namePrefix' => $this->namePrefix(),
            'minute' => $minute,
        ]);
    }

    public function minutePdf(Minute $minute): StreamedResponse
    {
        $this->authorize('downloadPdf', $minute);
        abort_unless($minute->pdf_path && Storage::disk('local')->exists($minute->pdf_path), 404);

        return Storage::disk('local')->download($minute->pdf_path, 'ata-'.$minute->id.'.pdf');
    }

    public function minuteAttachmentDownload(Minute $minute, MinuteAttachment $minute_attachment): StreamedResponse
    {
        abort_unless((int) $minute_attachment->minute_id === (int) $minute->id, 404);
        $this->authorize('view', $minute);
        abort_unless(Storage::disk('local')->exists($minute_attachment->path), 404);

        return Storage::disk('local')->download(
            $minute_attachment->path,
            $minute_attachment->original_name ?? 'anexo'
        );
    }

    public function convocations()
    {
        $this->authorize('viewAny', Convocation::class);
        $convocations = Convocation::query()->where('status', 'published')->orderByDesc('assembly_at')->paginate(15);

        return view('secretaria::painel-operacional.convocations-index', [
            'layout' => $this->layout(),
            'homeRoute' => $this->homeRoute(),
            'namePrefix' => $this->namePrefix(),
            'convocations' => $convocations,
        ]);
    }

    public function convocationShow(Convocation $convocation)
    {
        $this->authorize('view', $convocation);

        return view('secretaria::painel-operacional.convocation-show', [
            'layout' => $this->layout(),
            'homeRoute' => $this->homeRoute(),
            'namePrefix' => $this->namePrefix(),
            'convocation' => $convocation,
        ]);
    }

    public function documents()
    {
        $this->authorize('viewAny', SecretariaDocument::class);
        $q = SecretariaDocument::query()->orderByDesc('created_at');
        if (auth()->user()->hasRole('jovens')) {
            $q->where('is_public', true);
        } else {
            $q->where('is_public', true);
        }
        if (user_needs_secretaria_church_scope()) {
            $user = auth()->user();
            $ids = $user->churchIdsForSecretariaScope();
            $q->where(function ($sub) use ($ids) {
                $sub->whereNull('igreja_id');
                if ($ids !== []) {
                    $sub->orWhereIn('igreja_id', $ids);
                }
            });
        }
        $documents = $q->paginate(15);

        return view('secretaria::painel-operacional.documents-index', [
            'layout' => $this->layout(),
            'homeRoute' => $this->homeRoute(),
            'namePrefix' => $this->namePrefix(),
            'documents' => $documents,
        ]);
    }

    public function documentDownload(SecretariaDocument $document): StreamedResponse
    {
        $this->authorize('download', $document);
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->download($document->path, $document->title.'.pdf');
    }
}
