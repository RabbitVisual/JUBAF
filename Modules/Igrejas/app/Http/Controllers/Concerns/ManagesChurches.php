<?php

namespace Modules\Igrejas\App\Http\Controllers\Concerns;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\ErpChurchScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Igrejas\App\Http\Requests\StoreChurchRequest;
use Modules\Igrejas\App\Http\Requests\UpdateChurchRequest;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;
use Modules\Igrejas\App\Models\JubafSector;
use Modules\Igrejas\App\Repositories\ChurchRepository;
use Modules\Igrejas\App\Services\ChurchService;
use Modules\Secretaria\App\Models\Minute;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ManagesChurches
{
    abstract protected function routePrefix(): string;

    abstract protected function viewPrefix(): string;

    abstract protected function panelLayout(): string;

    protected function churchRepository(): ChurchRepository
    {
        return app(ChurchRepository::class);
    }

    protected function churchService(): ChurchService
    {
        return app(ChurchService::class);
    }

    /**
     * Dados extra para a vista index (ex.: estatísticas no painel admin).
     *
     * @param  LengthAwarePaginator<int, Church>  $churches
     * @return array<string, mixed>
     */
    protected function indexExtraData(Request $request, $churches, array $filters): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function mergeChurchMediaFromRequest(Request $request, Church $church, array &$data): void
    {
        if ($request->hasFile('logo')) {
            if ($church->exists && $church->logo_path) {
                Storage::disk('public')->delete($church->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('churches/logos', 'public');
        }

        if ($request->hasFile('cover')) {
            if ($church->exists && $church->cover_path) {
                Storage::disk('public')->delete($church->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('churches/covers', 'public');
        }
    }

    /** @return Collection<int, Church> */
    protected function churchesForParentSelect(?Church $exclude = null): Collection
    {
        $q = Church::query()
            ->where('kind', Church::KIND_CHURCH)
            ->orderBy('name');

        if ($exclude) {
            $q->whereKeyNot($exclude->getKey());
        }

        if (auth()->check()) {
            ErpChurchScope::applyToChurchQuery($q, auth()->user());
        }

        return $q->get(['id', 'name']);
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return Collection<int, JubafSector>
     */
    protected function jubafSectorsForForm(): Collection
    {
        if (! Schema::hasTable('jubaf_sectors')) {
            return collect();
        }

        return JubafSector::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    }

    protected function auditChurchSnapshot(Church $church): array
    {
        $keys = [
            'id', 'uuid', 'name', 'legal_name', 'trade_name', 'slug', 'kind', 'parent_church_id', 'cnpj', 'logo_path', 'cover_path',
            'sector', 'jubaf_sector_id', 'foundation_date', 'cooperation_status', 'crm_status',
            'pastor_user_id', 'unijovem_leader_user_id',
            'city', 'state', 'country', 'postal_code', 'street', 'number', 'complement', 'district',
            'address', 'phone', 'email',
            'asbaf_notes', 'is_active', 'joined_at', 'metadata',
        ];

        return array_intersect_key($church->getAttributes(), array_flip($keys));
    }

    protected function applyChurchListFilters(Request $request, Builder $q): void
    {
        $this->churchRepository()->applyListFilters($request, $q);
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Church::class);

        $user = $request->user();
        abort_unless($user, 403);

        $churches = $this->churchRepository()->paginateForUser($user, $request, 20);

        $filters = $request->only(['search', 'active', 'city', 'sector', 'jubaf_sector_id', 'cooperation_status', 'crm_status']);

        return view($this->viewPrefix().'.index', array_merge([
            'churches' => $churches,
            'filters' => $filters,
            'jubafSectors' => $this->jubafSectorsForForm(),
            'routePrefix' => $this->routePrefix(),
            'layout' => $this->panelLayout(),
        ], $this->indexExtraData($request, $churches, $filters)));
    }

    public function create()
    {
        $this->authorize('create', Church::class);

        return view($this->viewPrefix().'.create', [
            'church' => new Church(['kind' => Church::KIND_CHURCH]),
            'leadershipUsers' => User::query()->orderBy('name')->limit(500)->get(['id', 'name']),
            'parentChurches' => $this->churchesForParentSelect(),
            'jubafSectors' => $this->jubafSectorsForForm(),
            'routePrefix' => $this->routePrefix(),
            'layout' => $this->panelLayout(),
        ]);
    }

    public function store(StoreChurchRequest $request)
    {
        $this->authorize('create', Church::class);

        $data = $request->validated();
        unset($data['logo'], $data['cover']);
        if (! $request->user()->can('igrejas.activate')) {
            unset($data['is_active']);
        }

        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }

        $church = new Church;
        $this->mergeChurchMediaFromRequest($request, $church, $data);

        $church = $this->churchService()->createChurch($data);

        AuditLog::log(
            'igrejas.church.create',
            Church::class,
            $church->id,
            'igrejas',
            "Congregação «{$church->name}» criada (id {$church->id}).",
            null,
            $this->auditChurchSnapshot($church)
        );

        return redirect()
            ->route($this->routePrefix().'.show', $church)
            ->with('success', 'Igreja criada com sucesso.');
    }

    public function show(Church $church)
    {
        $this->authorize('view', $church);

        $church->load(['pastor', 'unijovemLeader']);
        $church->loadCount(['users', 'jovensMembers', 'leaders']);

        $membersQuery = $church->users()->with('roles')->orderBy('name');
        $members = $membersQuery->paginate(25)->withQueryString();

        $localLeadership = $church->users()
            ->with('roles')
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['pastor', 'lider']))
            ->orderBy('name')
            ->get();

        $upcomingEvents = collect();
        if (Schema::hasTable('calendar_events')) {
            $upcomingEvents = CalendarEvent::query()
                ->where('church_id', $church->id)
                ->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->limit(8)
                ->get();
        }

        $financeSummary = null;
        if (Schema::hasTable('fin_transactions')) {
            $financeSummary = [
                'in_sum' => (float) FinTransaction::query()
                    ->where('church_id', $church->id)
                    ->where('direction', 'in')
                    ->sum('amount'),
                'out_sum' => (float) FinTransaction::query()
                    ->where('church_id', $church->id)
                    ->where('direction', 'out')
                    ->sum('amount'),
            ];
        }

        $pendingRequestsCount = $church->changeRequests()
            ->where('status', ChurchChangeRequest::STATUS_SUBMITTED)
            ->count();

        $cotasSummary = $church->cotasSummary();
        $secretariaDocumentsCount = $church->secretariaDocumentsCount();
        $secretariaMinutesCount = 0;
        if (Schema::hasTable('secretaria_minutes')) {
            $secretariaMinutesCount = Minute::query()
                ->where('church_id', $church->id)
                ->count();
        }

        return view($this->viewPrefix().'.show', [
            'church' => $church,
            'members' => $members,
            'localLeadership' => $localLeadership,
            'upcomingEvents' => $upcomingEvents,
            'financeSummary' => $financeSummary,
            'cotasSummary' => $cotasSummary,
            'secretariaDocumentsCount' => $secretariaDocumentsCount,
            'secretariaMinutesCount' => $secretariaMinutesCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'routePrefix' => $this->routePrefix(),
            'layout' => $this->panelLayout(),
        ]);
    }

    public function exportMembersCsv(Church $church, Request $request): StreamedResponse
    {
        $this->authorize('view', $church);

        AuditLog::log(
            'igrejas.church.members_export',
            Church::class,
            $church->id,
            'igrejas',
            "Exportação CSV de membros da congregação «{$church->name}».",
            null,
            null
        );

        $filename = 'igreja-'.$church->id.'-membros-'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $cols = ['name', 'email', 'phone', 'birth_date', 'roles', 'church_id'];

        return response()->streamDownload(function () use ($church, $cols) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $cols);
            foreach ($church->users()->with('roles')->orderBy('name')->cursor() as $u) {
                fputcsv($out, [
                    $u->name,
                    $u->email,
                    $u->phone ?? '',
                    $u->birth_date?->format('Y-m-d') ?? '',
                    $u->roles->pluck('name')->implode(', '),
                    $u->church_id,
                ]);
            }
            fclose($out);
        }, $filename, $headers);
    }

    public function edit(Church $church)
    {
        $this->authorize('update', $church);

        return view($this->viewPrefix().'.edit', [
            'church' => $church,
            'leadershipUsers' => User::query()->orderBy('name')->limit(500)->get(['id', 'name']),
            'parentChurches' => $this->churchesForParentSelect($church),
            'jubafSectors' => $this->jubafSectorsForForm(),
            'routePrefix' => $this->routePrefix(),
            'layout' => $this->panelLayout(),
        ]);
    }

    public function update(UpdateChurchRequest $request, Church $church)
    {
        $this->authorize('update', $church);

        if ($request->has('is_active') && (bool) $request->input('is_active') !== (bool) $church->is_active) {
            $this->authorize('activate', $church);
        }

        $data = $request->validated();
        unset($data['logo'], $data['cover']);
        if (! $request->user()->can('igrejas.activate')) {
            unset($data['is_active']);
        }

        if (! empty($data['name']) && $data['name'] !== $church->name && empty($request->input('slug'))) {
            $data['slug'] = Church::uniqueSlugFromName($data['name']);
        }

        $oldSnapshot = $this->auditChurchSnapshot($church);

        $this->mergeChurchMediaFromRequest($request, $church, $data);

        $church = $this->churchService()->updateChurch($church, $data);

        AuditLog::log(
            'igrejas.church.update',
            Church::class,
            $church->id,
            'igrejas',
            "Congregação «{$church->name}» atualizada (id {$church->id}).",
            $oldSnapshot,
            $this->auditChurchSnapshot($church)
        );

        return redirect()
            ->route($this->routePrefix().'.show', $church)
            ->with('success', 'Igreja atualizada.');
    }

    public function destroy(Church $church)
    {
        $this->authorize('delete', $church);

        $oldSnapshot = $this->auditChurchSnapshot($church);
        $label = $church->name;
        $id = $church->id;

        $church->delete();

        AuditLog::log(
            'igrejas.church.delete',
            Church::class,
            $id,
            'igrejas',
            "Congregação «{$label}» removida (soft delete, id {$id}).",
            $oldSnapshot,
            null
        );

        return redirect()
            ->route($this->routePrefix().'.index')
            ->with('success', 'Igreja removida.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $this->authorize('export', Church::class);

        AuditLog::log(
            'igrejas.church.export',
            null,
            null,
            'igrejas',
            'Exportação CSV do cadastro de congregações.',
            ['filters' => $request->only(['search', 'active', 'city', 'sector', 'jubaf_sector_id', 'cooperation_status'])],
            null
        );

        $filename = 'igrejas-jubaf-'.now()->format('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $columns = ['name', 'legal_name', 'trade_name', 'slug', 'sector', 'cooperation_status', 'crm_status', 'city', 'phone', 'email', 'is_active', 'joined_at', 'foundation_date'];

        return response()->streamDownload(function () use ($columns, $request) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $columns);
            $user = auth()->user();
            abort_unless($user, 403);
            $q = $this->churchRepository()->exportQuery($user, $request);
            foreach ($q->cursor() as $c) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = match ($col) {
                        'is_active' => $c->is_active ? '1' : '0',
                        'joined_at' => $c->joined_at?->format('Y-m-d') ?? '',
                        'foundation_date' => $c->foundation_date?->format('Y-m-d') ?? '',
                        default => ($c->{$col} ?? '') === null ? '' : (string) $c->{$col},
                    };
                }
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, $headers);
    }
}
