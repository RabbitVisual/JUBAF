<?php

namespace Tests\Unit;

use Modules\Financeiro\App\Events\FinancialObligationGenerated;
use Modules\Financeiro\App\Events\FinancialObligationPaid;
use Modules\Igrejas\App\Events\LeaderAssignedToChurch;
use PHPUnit\Framework\TestCase;

class ErpBusinessEventsTest extends TestCase
{
    public function test_erp_domain_event_classes_exist(): void
    {
        $this->assertTrue(class_exists(FinancialObligationGenerated::class));
        $this->assertTrue(class_exists(FinancialObligationPaid::class));
        $this->assertTrue(class_exists(LeaderAssignedToChurch::class));
    }
}
