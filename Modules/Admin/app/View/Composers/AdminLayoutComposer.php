<?php

namespace Modules\Admin\App\View\Composers;

use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Admin\App\Support\AdminNavigationBuilder;

final class AdminLayoutComposer
{
    public function __construct(
        private AdminNavigationBuilder $adminNavigationBuilder
    ) {}

    public function compose(View $view): void
    {
        $name = Route::currentRouteName() ?? '';
        if (! str_starts_with($name, 'admin.')) {
            return;
        }

        $view->with('adminMenu', [
            'sections' => $this->adminNavigationBuilder->build(),
        ]);
    }
}
