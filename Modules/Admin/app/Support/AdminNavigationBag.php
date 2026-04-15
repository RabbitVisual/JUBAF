<?php

namespace Modules\Admin\App\Support;

/**
 * Mutable bag passed to {@see \Modules\Admin\App\Events\AdminNavigationBuilding} so other modules can append sections or items.
 */
final class AdminNavigationBag
{
    /**
     * @param  list<array<string, mixed>>  $sections
     */
    public function __construct(
        public array $sections
    ) {}

    /**
     * @param  array<string, mixed>  $section
     */
    public function pushSection(array $section): void
    {
        $this->sections[] = $section;
    }
}
