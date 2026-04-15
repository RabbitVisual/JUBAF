<?php

namespace Tests\Feature\Diretoria;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DiretoriaPanelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function directoria_member_can_access_dashboard(): void
    {
        Role::firstOrCreate(['name' => 'presidente', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('presidente');

        $this->actingAs($user)
            ->get(route('diretoria.dashboard'))
            ->assertOk();
    }

    #[Test]
    public function standard_user_cannot_access_diretoria_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('diretoria.dashboard'))
            ->assertForbidden();
    }

    #[Test]
    public function guest_is_redirected_to_login_from_diretoria(): void
    {
        $this->get(route('diretoria.dashboard'))
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function legacy_co_admin_role_still_accesses_diretoria_until_removed(): void
    {
        Role::firstOrCreate(['name' => 'co-admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('co-admin');

        $this->actingAs($user)
            ->get(route('diretoria.dashboard'))
            ->assertOk();
    }

    #[Test]
    public function co_admin_url_redirects_to_diretoria(): void
    {
        Role::firstOrCreate(['name' => 'presidente', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('presidente');

        $this->actingAs($user)
            ->get('/co-admin/dashboard')
            ->assertRedirect('/diretoria/dashboard');
    }

    #[Test]
    public function executive_can_open_users_route(): void
    {
        Role::firstOrCreate(['name' => 'presidente', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('presidente');

        $this->actingAs($user)
            ->get(route('diretoria.users.index'))
            ->assertOk();
    }

    #[Test]
    public function secretary_cannot_open_executive_users_route(): void
    {
        Role::firstOrCreate(['name' => 'secretario-1', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('secretario-1');

        $this->actingAs($user)
            ->get(route('diretoria.users.index'))
            ->assertForbidden();
    }
}
