<?php

namespace Modules\Financeiro\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Financeiro\App\Http\Requests\StoreFinCategoryRequest;
use Modules\Financeiro\App\Http\Requests\UpdateFinCategoryRequest;
use Modules\Financeiro\App\Models\FinCategory;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', FinCategory::class);

        $categories = FinCategory::query()
            ->withCount('transactions')
            ->orderBy('group_key')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('financeiro::paineldiretoria.categories.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', FinCategory::class);

        return view('financeiro::paineldiretoria.categories.create', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'category' => new FinCategory([
                'direction' => 'in',
                'sort_order' => 100,
                'is_active' => true,
                'is_system' => false,
            ]),
        ]);
    }

    public function store(StoreFinCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_system'] = false;
        $data['sort_order'] = $data['sort_order'] ?? 100;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['code'] = ! empty($data['code']) ? $data['code'] : null;
        $data['group_key'] = ! empty($data['group_key']) ? $data['group_key'] : null;

        FinCategory::query()->create($data);

        return redirect()
            ->route('diretoria.financeiro.categories.index')
            ->with('success', 'Categoria criada.');
    }

    public function edit(FinCategory $category): View
    {
        $this->authorize('update', $category);

        return view('financeiro::paineldiretoria.categories.edit', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.financeiro',
            'category' => $category,
        ]);
    }

    public function update(UpdateFinCategoryRequest $request, FinCategory $category): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? $category->sort_order;
        $data['is_active'] = $request->boolean('is_active', $category->is_active);
        $data['code'] = ! empty($data['code']) ? $data['code'] : null;
        $data['group_key'] = ! empty($data['group_key']) ? $data['group_key'] : null;

        if ($category->is_system) {
            unset($data['code'], $data['direction']);
        }

        $category->update($data);

        return redirect()
            ->route('diretoria.financeiro.categories.index')
            ->with('success', 'Categoria actualizada.');
    }

    public function destroy(FinCategory $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('diretoria.financeiro.categories.index')
            ->with('success', 'Categoria removida.');
    }
}
