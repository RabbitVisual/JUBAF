<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacEscalationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function president_cannot_assign_super_admin_role_via_user_update(): void
    {
        $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);

        $presidente = User::where('email', 'coadmin@jubaf.local')->first();
        $lider = User::where('email', 'lider@jubaf.local')->first();

        $this->actingAs($presidente)
            ->put(route('diretoria.users.update', $lider), [
                'first_name' => $lider->first_name,
                'last_name' => $lider->last_name,
                'email' => $lider->email,
                'active' => true,
                'roles' => ['super-admin'],
            ])
            ->assertSessionHas('error');

        $this->assertFalse($lider->fresh()->hasRole('super-admin'));
        $this->assertTrue($lider->fresh()->hasRole('lider'));
    }

    #[Test]
    public function president_cannot_open_super_admin_user_edit(): void
    {
        $this->seed(\Database\Seeders\RolesPermissionsSeeder::class);

        $presidente = User::where('email', 'coadmin@jubaf.local')->first();
        $admin = User::where('email', 'admin@jubaf.local')->first();

        $this->actingAs($presidente)
            ->get(route('diretoria.users.edit', $admin))
            ->assertForbidden();
    }

    #[Test]
    public function super_admin_hub_route_is_registered(): void
    {
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $u = User::factory()->create();
        $u->assignRole('super-admin');

        $this->actingAs($u)
            ->get(route('admin.seguranca.hub'))
            ->assertOk();
    }
}
