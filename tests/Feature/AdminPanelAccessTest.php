<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nwidart\Modules\Facades\Module;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_admin_dashboard_and_bible_index_when_bible_module_enabled(): void
    {
        $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)->get('/admin')->assertOk();

        if (Module::isEnabled('Bible')) {
            $this->actingAs($user)->get(route('admin.bible.index'))->assertOk();
        }
    }

    public function test_user_without_super_admin_cannot_access_super_admin_only_admin_routes(): void
    {
        $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('pastor');

        if (Module::isEnabled('Bible')) {
            $this->actingAs($user)->get(route('admin.bible.index'))->assertForbidden();
        }

        $this->actingAs($user)->get('/admin/modules')->assertForbidden();
    }
}
