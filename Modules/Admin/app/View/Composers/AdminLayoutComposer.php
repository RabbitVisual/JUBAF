<?php

namespace Modules\Admin\App\View\Composers;

use Illuminate\View\View;
use Modules\Admin\App\Support\AdminNavigationBuilder;

final class AdminLayoutComposer
{
    public function __construct(
        private AdminNavigationBuilder $adminNavigationBuilder
    ) {}

    public function compose(View $view): void
    {
        $view->with('adminMenu', [
            'sections' => $this->adminNavigationBuilder->build(),
        ]);
    }
}
