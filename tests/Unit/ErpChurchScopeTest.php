<?php

namespace Tests\Unit;

use App\Support\ErpChurchScope;
use PHPUnit\Framework\TestCase;

class ErpChurchScopeTest extends TestCase
{
    public function test_erp_church_scope_class_is_loadable(): void
    {
        $this->assertTrue(class_exists(ErpChurchScope::class));
    }
}
