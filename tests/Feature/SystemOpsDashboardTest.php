<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemOpsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_ops_dashboard_forbidden_without_super_admin(): void
    {
        $this->seed(RolesPermissionsSeeder::class);
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.ops.index'))->assertForbidden();
    }

    public function test_ops_dashboard_ok_for_super_admin(): void
    {
        $this->seed(RolesPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $response = $this->actingAs($user)->get(route('admin.ops.index'));

        $response->assertOk();
        $response->assertSee('Painel técnico', false);
    }
}
