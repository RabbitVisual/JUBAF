<?php

namespace Modules\Igrejas\App\Http\Controllers\Concerns;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\ErpChurchScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Igrejas\App\Http\Requests\StoreChurchRequest;
use Modules\Igrejas\App\Http\Requests\UpdateChurchRequest;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\JubafSector;
use Modules\Igrejas\App\Services\ChurchLeadershipSync;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ManagesChurches
{
    abstract protected function routePrefix(): string;

    abstract protected function viewPrefix(): string;

    abstract protected function panelLayout(): string;

    /**
     * Dados extra para a vista index (ex.: estatísticas no painel admin).
     *
     * @param  \Illuminate\Contracts\Pagination\LengthAwarePaginator<int, Church>  $churches
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

    /** @return \Illuminate\Support\Collection<int, Church> */
    protected function churchesForParentSelect(?Church $exclude = null): \Illuminate\Support\Collection
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
     * @return \Illuminate\Support\Collection<int, JubafSector>
     */
    protected function jubafSectorsForForm(): \Illuminate\Support\Collection
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('jubaf_sectors')) {
            return collect();
        }

        return JubafSector::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
    }

    protected function auditChurchSnapshot(Church $church): array
    {
        $keys = [
            'id', 'name', 'slug', 'kind', 'parent_church_id', 'cnpj', 'logo_path', 'cover_path',
            'sector', 'jubaf_sector_id', 'foundation_date', 'cooperation_status',
            'pastor_user_id', 'unijovem_leader_user_id',
            'city', 'address', 'phone', 'email',
            'asbaf_notes', 'is_active', 'joined_at', 'metadata',
        ];

        return array_intersect_key($church->getAttributes(), array_flip($keys));
    }

    protected function applyChurchListFilters(Request $request, Builder $q): void
    {
        if ($request->filled('search')) {
            $s = $request->string('search');
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', '%'.$s.'%')
                    ->orWhere('city', 'like', '%'.$s.'%')
                    ->orWhere('email', 'like', '%'.$s.'%');
            });
        }

        if ($request->filled('active')) {
            $q->where('is_active', $request->boolean('active'));
        }

        if ($request->filled('city')) {
            $q->where('city', 'like', '%'.$request->string('city').'%');
        }

        if ($request->filled('sector')) {
            $q->where('sector', 'like', '%'.$request->string('sector').'%');
        }

        if ($request->filled('jubaf_sector_id')) {
            $q->where('jubaf_sector_id', (int) $request->input('jubaf_sector_id'));
        }

        if ($request->filled('cooperation_status')) {
            $q->where('cooperation_status', $request->string('cooperation_status'));
        }
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Church::class);

        $q = Church::query()->withCount(['users', 'jovensMembers', 'leaders']);
        if ($request->user()) {
            ErpChurchScope::applyToChurchQuery($q, $request->user());
        }
        $this->applyChurchListFilters($request, $q);

        $churches = $q->orderBy('name')->paginate(20)->withQueryString();

        $filters = $request->only(['search', 'active', 'city', 'sector', 'jubaf_sector_id', 'cooperation_status']);

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

        $church = Church::create($data);
        $church->refresh();
        ChurchLeadershipSync::syncFromChurch($church);

        if ($church->jubaf_sector_id) {
            event(new \Modules\Igrejas\App\Events\ChurchSectorAssigned($church, null));
        }

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

        $upcomingEvents = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('calendar_events')) {
            $upcomingEvents = \Modules\Calendario\App\Models\CalendarEvent::query()
                ->where('church_id', $church->id)
                ->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->limit(8)
                ->get();
        }

        $financeSummary = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('fin_transactions')) {
            $financeSummary = [
                'in_sum' => (float) \Modules\Financeiro\App\Models\FinTransaction::query()
                    ->where('church_id', $church->id)
                    ->where('direction', 'in')
                    ->sum('amount'),
                'out_sum' => (float) \Modules\Financeiro\App\Models\FinTransaction::query()
                    ->where('church_id', $church->id)
                    ->where('direction', 'out')
                    ->sum('amount'),
            ];
        }

        $pendingRequestsCount = $church->changeRequests()
            ->where('status', \Modules\Igrejas\App\Models\ChurchChangeRequest::STATUS_SUBMITTED)
            ->count();

        return view($this->viewPrefix().'.show', [
            'church' => $church,
            'members' => $members,
            'upcomingEvents' => $upcomingEvents,
            'financeSummary' => $financeSummary,
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
        $previousSectorId = $church->jubaf_sector_id;

        $this->mergeChurchMediaFromRequest($request, $church, $data);

        $church->update($data);
        $church->refresh();
        ChurchLeadershipSync::syncFromChurch($church);

        if ($church->wasChanged('jubaf_sector_id')) {
            event(new \Modules\Igrejas\App\Events\ChurchSectorAssigned($church, $previousSectorId));
        }

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

        $columns = ['name', 'slug', 'sector', 'cooperation_status', 'city', 'phone', 'email', 'is_active', 'joined_at', 'foundation_date'];

        return response()->streamDownload(function () use ($columns, $request) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $columns);
            $q = Church::query();
            if (auth()->check()) {
                ErpChurchScope::applyToChurchQuery($q, auth()->user());
            }
            $this->applyChurchListFilters($request, $q);
            foreach ($q->orderBy('name')->cursor() as $c) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = match ($col) {
                        'is_active' => $c->is_active ? '1' : '0',
                        'joined_at' => $c->joined_at?->format('Y-m-d') ?? '',
                        'foundation_date' => $c->foundation_date?->format('Y-m-d') ?? '',
                        default => $c->{$col} ?? '',
                    };
                }
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, $headers);
    }
}
